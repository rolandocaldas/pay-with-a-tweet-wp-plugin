<?php
/**
 * Pay With a Tweet - Download
 *
 * @author Rolando Caldas Sánchez <rolando.caldas@gmail.com>
 * @package Pay With a Tweet
 * @version 1.0
 * @since 1.0
 * @copyright (c) 2013, Rolando Caldas
 * @license http://opensource.org/licenses/GPL-2.0 GPL2
 * @filesource
 */

/** Make sure that the WordPress bootstrap has run before continuing. */
require_once('../../../wp-load.php' );
require_once('./pay-with-tweet.php');
require_once( PWT_PLUGIN_PATH . '/lib/twitteroauth.php');

$action = $_GET['action'];
$id = intval($_GET['id']);
$errors = new WP_Error();

add_filter('404_template', 'pwt_download_404_template');

switch ($action) {
    
    case 'get_access':
        
        unset($_SESSION['oauth_token']);
        unset($_SESSION['oauth_token_secret']);
        unset($_SESSION['oauth_pid']);
        
        $_SESSION['oauth_pid'] = $id;
        
        $consumerkey = get_option(PWT_PLUGIN . '_twitter_consumerkey', '');
        $consumersecret = get_option(PWT_PLUGIN . '_twitter_consumersecret', '');
        
        /* Build TwitterOAuth object with client credentials. */
        $connection = new TwitterOAuth($consumerkey, $consumersecret);
 
        /* Get temporary credentials. */
        $request_token = $connection->getRequestToken(PWT_PLUGIN_URL . '/download.php?id=' . $id . '&action=callback');
        
        /* Save temporary credentials to session. */
        $_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
        $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
 
        /* If last connection failed don't display authorization link. */
        if ($connection->http_code == 200) {
            /* Build authorize URL and redirect user to Twitter. */
            $url = $connection->getAuthorizeURL($token);
            header('Location: ' . $url); 
            exit;
        
        } else {
            
            $GLOBALS['pwt_error'] = $connection;
            apply_filters( 'template_include', get_404_template() );
            exit;
        }    

        break;
    
    case 'callback' :
        //$_SESSION['oauth_pid'] = $project->pid;
    
        if ($_SESSION['oauth_pid'] != $_GET['id']) {
            
            exit;
        }
        
        /* If the oauth_token is old redirect to the connect page. */
        if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
            unset($_SESSION['oauth_token']);
            unset($_SESSION['oauth_token_secret']);        
            define('WP_USE_THEMES', true);
            $template = apply_filters( 'template_include', get_404_template() );
            include( $template );
            exit;
        }
        
        global $wpdb; 
        $button = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "pwt_button WHERE id='" . intval($_GET['id']) . "'");

        
        $consumerkey = get_option(PWT_PLUGIN . '_twitter_consumerkey', '');
        $consumersecret = get_option(PWT_PLUGIN . '_twitter_consumersecret', '');
            
        /* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
        $connection = new TwitterOAuth($consumerkey, $consumersecret, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

    
        /* Request access tokens from twitter */
        $access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
    
        
        $connection2 = new TwitterOAuth($consumerkey, $consumersecret, $access_token['oauth_token'], $access_token['oauth_token_secret']);
        $twitter_user = $connection2->get('account/verify_credentials'); 

        $status = $connection2->post('statuses/update', array('status' => $button[0]->message)); 
        
        /* Remove no longer needed request tokens */
        unset($_SESSION['oauth_token']);
        unset($_SESSION['oauth_token_secret']);
        unset($_SESSION['oauth_pid']);

        /* If HTTP response is 200 continue otherwise send to connect page to retry */
        if (200 == $connection2->http_code) {
            $wpdb->insert(
                $wpdb->prefix . "pwt_button_stats", 
                array( 
                    'bid' => intval($_GET['id']),
                    'time' => current_time( 'mysql' ), 
                    'name' => '@' . $row['screen_name']
                ), 
                array( 
                    '%d', 
                    '%s',
                    '%s'
                ) 
            );
                    
            $mime = 'application/force-download';
            if (class_exists('finfo') && version_compare(PHP_VERSION, '5.3.0') >= 0) {
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mime = $finfo->file(PWT_PLUGIN_UPLOAD_DIR . '/' . $button[0]->download);
            }
            $download = file_get_contents(PWT_PLUGIN_UPLOAD_DIR . '/' . $button[0]->download);

            nocache_headers();

            header('Content-type: ' . $mime);
            header("Content-Description: File Transfer");             
            header("Content-Length: " . filesize(PWT_PLUGIN_UPLOAD_DIR . '/' . $button[0]->download));
            header('Content-Disposition: attachment; filename="' . $button[0]->download . '"');

            print $download;
            exit;
            
        } else {
            $GLOBALS['pwt_error'] = $connection;
            apply_filters( 'template_include', get_404_template() );
            exit;
        }    
        break;
}
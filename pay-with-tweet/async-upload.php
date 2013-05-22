<?php

/**
 * Accepts file uploads from swfupload or other asynchronous upload methods.
 * Modification of WordPress Administration async-upload 
 * 
 * @author Rolando Caldas Sánchez <rolando.caldas@gmail.com>
 * @package Pay With a Tweet
 * @version 1.0
 * @since 1.0
 * @copyright (c) 2013, Rolando Caldas
 * @license http://opensource.org/licenses/GPL-2.0 GPL2
 * @filesource
 */

define('WP_ADMIN', true);

if ( defined('ABSPATH') )
	require_once(ABSPATH . 'wp-load.php');
else
	require_once('../../../wp-load.php');

if ( ! ( isset( $_REQUEST['action'] ) && 'upload-attachment' == $_REQUEST['action'] ) ) {
	// Flash often fails to send cookies with the POST or upload, so we need to pass it in GET or POST instead
	if ( is_ssl() && empty($_COOKIE[SECURE_AUTH_COOKIE]) && !empty($_REQUEST['auth_cookie']) )
		$_COOKIE[SECURE_AUTH_COOKIE] = $_REQUEST['auth_cookie'];
	elseif ( empty($_COOKIE[AUTH_COOKIE]) && !empty($_REQUEST['auth_cookie']) )
		$_COOKIE[AUTH_COOKIE] = $_REQUEST['auth_cookie'];
	if ( empty($_COOKIE[LOGGED_IN_COOKIE]) && !empty($_REQUEST['logged_in_cookie']) )
		$_COOKIE[LOGGED_IN_COOKIE] = $_REQUEST['logged_in_cookie'];
	unset($current_user);
}

require_once('../../../wp-admin/admin.php');
include ABSPATH . 'wp-content/plugins/pay-with-tweet/pay-with-tweet.php';

add_filter('upload_dir', 'pwt_upload_dir', 21);
remove_filter('upload_dir', 'pwt_upload_dir');

if ( !current_user_can('upload_files') )
	wp_die(__('You do not have permission to upload files.'));

header('Content-Type: text/html; charset=' . get_option('blog_charset'));

if ( isset( $_REQUEST['action'] ) && 'upload-attachment' === $_REQUEST['action'] ) {
    // @TODO
	define( 'DOING_AJAX', true );
	include ABSPATH . 'wp-admin/includes/ajax-actions.php';
        
	send_nosniff_header();
	nocache_headers();
        
	wp_ajax_upload_attachment();
	die( '0' );
}

check_admin_referer('media-form');

$result = pwt_media_handle_upload( 'async-upload', 0 );

if ( is_wp_error($result) ) {
    
    echo '
    <div class="error-div">
        <a class="dismiss" href="#" onclick="jQuery(this).parents(\'div.media-item\').slideUp(200, function(){jQuery(this).remove();});">' . __('Dismiss') . '</a>
        <strong>' . sprintf(__('&#8220;%s&#8221; has failed to upload due to an error'), esc_html($_FILES['async-upload']['name']) ) . '</strong><br />' .
	esc_html($result->get_error_message()) . '
    </div>';
    
} else {
    
    $value = esc_html($result['filename']) . (!empty($result['extension']) ? '.' . $result['extension'] : '');
    echo '
    <div class="error-div">
        <form method="post" action="' . admin_url('admin.php?page=pay-with-tweet/admin/pwt-create-button.php') . '" target="_blank">
            <input type="hidden" name="file" value="' . $value . '" />
        </form>
        <a class="dismiss" href="#" onclick="jQuery(this).parent().children(\'form\').submit();">' . __('Create payment button', PWT_PLUGIN) . '</a>
        <span class="title">' . $value . '</span>      
    </div>';
}
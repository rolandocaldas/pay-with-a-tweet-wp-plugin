<?php

/**
 * pwt-config.php
 * 
 * Screen to configure the twitter app used
 * 
 * @author Rolando Caldas Sánchez <rolando.caldas@gmail.com>
 * @package Pay With a Tweet
 * @subpackage Administration
 * @version 1.0
 * @since 1.0
 * @copyright (c) 2013, Rolando Caldas
 * @license http://opensource.org/licenses/GPL-2.0 GPL2
 * @filesource
 */

if (isset($_POST) && !empty($_POST)) {
    
    check_admin_referer();
    
    if (isset($_POST['twitter_consumerkey'])) {
        update_option( PWT_PLUGIN . '_twitter_consumerkey', $_POST['twitter_consumerkey'] );
    }

    if (isset($_POST['twitter_consumersecret'])) {
        update_option( PWT_PLUGIN . '_twitter_consumersecret', $_POST['twitter_consumersecret'] );
    }
    
}
    

$consumerkey = get_option(PWT_PLUGIN . '_twitter_consumerkey', '');
$consumersecret = get_option(PWT_PLUGIN . '_twitter_consumersecret', '');

?>
<div class="wrap">
    <?php screen_icon('options-general'); ?>
    <h2><?php print __('General Settings'); ?></h2>
    <form method="post" action="">
        <?php wp_nonce_field(); ?>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><label for="twitter_consumerkey">Twitter consumer key</label></th>
                    <td>
                        <input name="twitter_consumerkey" type="text" id="twitter_consumerkey" 
                               value="<?php print $consumerkey; ?>" class="regular-text">
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="twitter_consumersecret">Twitter consumer secret</label></th>
                    <td>
                        <input name="twitter_consumersecret" type="text" id="twitter_consumerkey" 
                               value="<?php print $consumersecret; ?>" class="regular-text">
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Callback URL</th>
                    <td>
                        <?php print PWT_PLUGIN_URL; ?>download.php
                    </td>
                </tr>
                
                
            </tbody>
        </table>
        <?php submit_button(); ?>
    </form>
    <div style="text-align:center;">
        <iframe width="560" height="315" src="http://www.youtube.com/embed/AO1_-vrLPZ0" frameborder="0" allowfullscreen></iframe>
    </div>
</div>
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

ob_clean();
 
add_filter('upload_dir', 'pwt_upload_dir', 21);
remove_filter('upload_dir', 'pwt_upload_dir');

if ( !current_user_can('upload_files') )
	wp_die(__('You do not have permcsission to upload files.'));

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
        <form method="post" action="' . admin_url('admin.php?page=pay-with-a-tweet/admin/pwt-create-button.php') . '" target="_blank">
            <input type="hidden" name="file" value="' . $value . '" />
        </form>
        <a class="dismiss" href="#" onclick="jQuery(this).parent().children(\'form\').submit();">' . __('Create payment button', PWT_PLUGIN) . '</a>
        <span class="title">' . $value . '</span>      
    </div>';
}

exit;
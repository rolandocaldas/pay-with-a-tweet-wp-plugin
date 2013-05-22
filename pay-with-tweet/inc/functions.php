<?php

/**
 * 
 * functions.php
 * 
 * File that contains all the global functions of the plugins
 * 
 * @author Rolando Caldas Sánchez <rolando.caldas@gmail.com>
 * @package Pay With a Tweet
 * @subpackage hooks
 * @version 1.0
 * @since 1.0
 * @copyright (c) 2013, Rolando Caldas
 * @license http://opensource.org/licenses/GPL-2.0 GPL2
 * @filesource
 */

/**
 * pwt_show_media_upload
 * 
 * 
 * @uses current_user_can Check if the user have perms to upload files
 * @uses wp_die Print the error page if the user can't upload files
 * @uses add_filter() Add the function pwt_upload_dir
 * @uses do_action() init media_upload_type
 * @deprecated since version 0.9
 */
function pwt_show_media_upload() {
    
if (!current_user_can('upload_files'))
	wp_die(__('You do not have permission to upload files.'));

    wp_enqueue_script('plupload-handlers');
    wp_enqueue_script('media-gallery');
    add_filter('upload_dir', 'pwt_upload_dir', 21);

    do_action("media_upload_type");
}

/**
 * pwt_media_handle_upload
 * 
 * This handles the file upload to download in a payment button
 *
 * @since 1.0
 *
 * @param string $file_id Index into the {@link $_FILES} array of the upload
 * @param int $post_id The post ID the media is associated with
 * @param array $post_data allows you to overwrite some of the attachment
 * @param array $overrides allows you to override the {@link wp_handle_upload()} behavior
 * @uses current_time() return the current time in mysql format
 * @uses wp_handle_upload() Upload the attachment
 * @throws WP_Error if the file couldn't be upload
 * @return string the pathinfo of the attachment
 */
function pwt_media_handle_upload($file_id, $post_id, $post_data = array(), $overrides = array( 'test_form' => false )) {

	$time = current_time('mysql');

	$name = $_FILES[$file_id]['name'];
	$file = wp_handle_upload($_FILES[$file_id], $overrides, $time);

        if ( isset($file['error']) )
		return new WP_Error( 'upload_error', $file['error'] );

	return pathinfo($file['file']);

}
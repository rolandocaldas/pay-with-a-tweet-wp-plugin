<?php

/**
 * pwt-upload.php
 * 
 * Screen to upload files to be used in pay with a tweet buttons
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

if (!current_user_can('upload_files'))
    wp_die(__('You do not have permission to upload files.'));

global $type, $tab, $pagenow, $is_IE, $is_opera;

$post_id = 0;
$_type = '';
$_tab = '';

$upload_size_unit = $max_upload_size = wp_max_upload_size();
$sizes = array('KB', 'MB', 'GB');

for ($u = -1; $upload_size_unit > 1024 && $u < count($sizes) - 1; $u++) {
    $upload_size_unit /= 1024;
}

if ($u < 0) {
    $upload_size_unit = 0;
    $u = 0;
} else {
    $upload_size_unit = (int) $upload_size_unit;
}

$form_class = 'media-upload-form type-form validate';

if (get_user_setting('uploader'))
    $form_class .= ' html-uploader';

wp_enqueue_script('plupload-handlers');

?>
<div class="wrap">
    <script type="text/javascript">post_id = 0;</script>
    <div id="icon-upload" class="icon32"><br /></div>
    <h2><?php print __('Upload files', PWT_PLUGIN); ?></h2>
<form enctype="multipart/form-data" method="post" action="" 
      class="<?php echo $form_class; ?>" id="<?php echo $type; ?>-form">
    <?php submit_button('', 'hidden', 'save', false); ?>
    <input type="hidden" name="post_id" id="post_id" value="<?php echo (int) $post_id; ?>" />
    <?php wp_nonce_field('media-form'); ?>
    <h3 class="media-title"><?php _e('Add media files from your computer'); ?></h3>

    <?php
    if (!_device_can_upload()) {
        echo '<p>' . __('The web browser on your device cannot be used to upload files. You may be able to use the <a href="http://wordpress.org/extend/mobile/">native app for your device</a> instead.') . '</p>';
        return;
    }
    ?>

    <div id="media-upload-notice"><?php
    if (isset($errors['upload_notice']))
        echo $errors['upload_notice'];
    ?></div>
    
    <div id="media-upload-error"><?php
        if (isset($errors['upload_error']) && is_wp_error($errors['upload_error']))
            echo $errors['upload_error']->get_error_message();
    ?></div>
    
    <?php
        if (is_multisite() && !is_upload_space_available()) {
            do_action('upload_ui_over_quota');
            return;
        }

        do_action('pre-upload-ui');

        $post_params = array(
            "post_id" => $post_id,
            "_wpnonce" => wp_create_nonce('media-form'),
            "type" => $_type,
            "tab" => $_tab,
            "short" => "1",
        ); // hook change! old name: 'swfupload_post_params'

        $plupload_init = array(
            'runtimes' => 'html5,silverlight,flash,html4',
            'browse_button' => 'plupload-browse-button',
            'container' => 'plupload-upload-ui',
            'drop_element' => 'drag-drop-area',
            'file_data_name' => 'async-upload',
            'multiple_queues' => true,
            'max_file_size' => $max_upload_size . 'b',
            'url' => esc_attr(PWT_PLUGIN_URL . 'async-upload.php'),
            'flash_swf_url' => includes_url('js/plupload/plupload.flash.swf'),
            'silverlight_xap_url' => includes_url('js/plupload/plupload.silverlight.xap'),
            'filters' => array(array('title' => __('Allowed Files'), 'extensions' => '*')),
            'multipart' => true,
            'urlstream_upload' => true,
            'multipart_params' => $post_params
        );

        // Multi-file uploading doesn't currently work in iOS Safari,
        // single-file allows the built-in camera to be used as source for images
        if (wp_is_mobile())
            $plupload_init['multi_selection'] = false;

        ?>

    <script type="text/javascript">
<?php
// Verify size is an int. If not return default value.
$large_size_h = absint(get_option('large_size_h'));
if (!$large_size_h)
    $large_size_h = 1024;
$large_size_w = absint(get_option('large_size_w'));
if (!$large_size_w)
    $large_size_w = 1024;
?>
        var resize_height = <?php echo $large_size_h; ?>, resize_width = <?php echo $large_size_w; ?>,
        wpUploaderInit = <?php echo json_encode($plupload_init); ?>;
    </script>

    <div id="plupload-upload-ui" class="hide-if-no-js">
        <div id="drag-drop-area">
            <div class="drag-drop-inside">
                <p class="drag-drop-info"><?php _e('Drop files here'); ?></p>
                <p><?php _ex('or', 'Uploader: Drop files here - or - Select Files'); ?></p>
                <p class="drag-drop-buttons"><input id="plupload-browse-button" type="button" value="<?php esc_attr_e('Select Files'); ?>" class="button" /></p>
            </div>
        </div>
    </div>

    <div id="html-upload-ui" class="hide-if-js">
        <p id="async-upload-wrap">
            <label class="screen-reader-text" for="async-upload"><?php _e('Upload'); ?></label>
            <input type="file" name="async-upload" id="async-upload" />
            <?php submit_button(__('Upload'), 'button', 'html-upload', false); ?>
            <a href="#" onclick="try{top.tb_remove();}catch(e){}; return false;"><?php _e('Cancel'); ?></a>
        </p>
        <div class="clear"></div>
    </div>

    <span class="max-upload-size"><?php printf(__('Maximum upload file size: %d%s.'), esc_html($upload_size_unit), esc_html($sizes[$u])); ?></span>
<?php if (($is_IE || $is_opera) && $max_upload_size > 100 * 1024 * 1024) { ?>
        <span class="big-file-warning"><?php _e('Your browser has some limitations uploading large files with the multi-file uploader. Please use the browser uploader for files over 100MB.'); ?></span>
    <?php } ?>

</form>
    <script type = "text/javascript">
        //<![CDATA[
        jQuery(function($){
            var preloaded = $(".media-item.preloaded");
            if ( preloaded.length > 0 ) {
                preloaded.each(function(){prepareMediaItem({id:this.id.replace(/[^0-9]/g, '')}, '');
                });
            }
            updateMediaForm();
        });
        //]]>
    </script>
    <div id="media-items">
        
    </div>
</div>
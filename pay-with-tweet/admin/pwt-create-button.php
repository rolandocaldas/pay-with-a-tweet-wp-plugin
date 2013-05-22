<?php

/**
 * pwt-create-button.php
 * 
 * Screen to create a new pay with a tweet button
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

global $wpdb;

$file = FALSE;
$message = NULL;
if (isset($_POST) && !empty($_POST)) {
    
    check_admin_referer();
    
    if (isset($_POST['_create_button']) && $_POST['_create_button'] == '1') {
        
        add_filter('upload_dir', 'pwt_upload_dir_image', 21);
        remove_filter('upload_dir', 'pwt_upload_dir_image');
        $image = pwt_media_handle_upload( 'new_image', 0 ); 

        if ( is_wp_error($image) ) {
            
            if (isset($_POST['image']) && !empty($_POST['image']) && file_exists(PWT_PLUGIN_UPLOAD_DIR . '/images/' . $_POST['image'])) {
                $image = $_POST['image'];
            } else {
                $image = '';
            }
            
        } else {
            $image = $image['basename'];
        }
        
        
        $wpdb->insert(
                $wpdb->prefix . "pwt_button", 
                array( 
                    'time' => current_time( 'mysql' ), 
                    'name' => $_POST['post_title'],
                    'message' => $_POST['twitter_message'],
                    'download' => $_POST['download'],
                    'image' => $image
                ), 
                array( 
                    '%s', 
                    '%s',
                    '%s',
                    '%s',
                    '%s'
                ) 
        );
        
        $_POST = array();
        
        $message = sprintf(__('New button created. You can manager all your buttons doing click <a href="%s">here</a>', PWT_PLUGIN), 
                admin_url('admin.php?page=pay-with-tweet/admin/pwt-buttons.php'));
                
    } else if (isset($_POST['file']) && !empty($_POST['file']) && file_exists(PWT_PLUGIN_UPLOAD_DIR . '/' . $_POST['file'])) {
        $file = PWT_PLUGIN_UPLOAD_DIR . '/' . $_POST['file'];
    }
    
}

wp_enqueue_script('dashboard');
$images = glob(PWT_PLUGIN_UPLOAD_DIR . '/images/*');
$files = glob(PWT_PLUGIN_UPLOAD_DIR . '/*');

if ($file && ($key = array_search($file, $files))) {
    unset($files[$key]);
    array_unshift($files, $file);
    unset($key);
}

?>

<div class="wrap">
    <div id="icon-upload" class="icon32"><br /></div>
    <h2><?php print __('New payment button', PWT_PLUGIN); ?></a></h2>
    <?php if ($message) : ?>
    <div id="message" class="updated"><p><?php echo $message; ?></p></div>    
    <?php endif; ?>
    <form method="post" id="form-payment-button-<?php print $key; ?>" 
          enctype="multipart/form-data"
          action="<?php print admin_url('admin.php?page=pay-with-tweet/admin/pwt-create-button.php'); ?>">
        <input type="hidden" name="_create_button" value="1" />
        <div id="poststuff">
            <div id="titlediv">
                <div id="titlewrap">
                    <label class="screen-reader-text" id="title-prompt-text" for="title"><?php print __( 'Enter title here' ); ?></label>
                    <input type="text" name="post_title" size="30" value="" id="title" autocomplete="off">
                </div>
            </div>          
            <div id="dashboard-widgets" class="metabox-holder columns-1">
                <div class="postbox-container">
                    <div class="postbox ">
                        <div class="handlediv" title="<?php print esc_attr__('Click to toggle'); ?>"><br></div>
                        <h3 class="hndle"><?php print __('Message to publish in Twitter', PWT_PLUGIN); ?></h3>
                        <div class="inside">
                            <label class="screen-reader-text" for="twitter_message"><?php print __('Message to publish in Twitter', PWT_PLUGIN); ?></label>
                            <span id="pwt-message-counter" class="alignright hide-if-no-js">0</span>
                            <textarea rows="4" cols="60" name="twitter_message" id="twitter_message"></textarea>
                            
                        </div>
                    </div>
                </div>
                <div class="postbox-container">
                    <div class="postbox closed">
                        <div class="handlediv" title="<?php print esc_attr__('Click to toggle'); ?>"><br></div>
                        <h3 class="hndle"><?php print __('Image', PWT_PLUGIN); ?></h3>
                        <div class="inside">
                            <label id="new_image_label" for="new_image"><?php print __('Upload the image to use:', PWT_PLUGIN); ?></label>
                            <input type="file" name="new_image" id="new_image" />
                            <div id="gallery-1" class="gallery galleryid-55 gallery-columns-4 gallery-size-thumbnail">
                            <?php $a = 0; ?>
                            <?php foreach ($images AS $value) : ?>
                                <?php $image_info = @getimagesize($value); ?>
                                <?php 
                                    if( !$image_info) :
                                        continue;
                                    endif; 
                                ?>
                                <?php if ($a == 4) : ?>
                                <br style="clear: both" />
                                <?php endif; ?>
                                <?php $path = pathinfo($value); ?>
                                <?php $filename = $path['filename'] . (!empty($path['extension']) ? '.' . $path['extension'] : ''); ?>
                                <dl class="gallery-item">
                                    <dt class="gallery-icon">
                                        <a href="<?php print PWT_PLUGIN_UPLOAD_URL . '/images/' . $filename; ?>" title="<?php $path['filename']; ?>"><img width="150" src="<?php print PWT_PLUGIN_UPLOAD_URL . '/images/' . $filename; ?>" class="attachment-thumbnail" alt="<?php $path['filename']; ?>" /></a>
                                        <br />
                                        <input type="radio" name="image" value="<?php print $filename; ?>" />
                                    </dt>
                                </dl>
                                <?php $a++; ?>
                            <?php endforeach; ?>
                                <br style="clear: both;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="postbox-container">
                    <div class="postbox closed">
                        <div class="handlediv" title="<?php print esc_attr__('Click to toggle'); ?>"><br></div>
                        <h3 class="hndle"><?php print __('File to download', PWT_PLUGIN); ?></h3>
                        <div class="inside" style="max-height: 365px; overflow: auto;">
                            <table class="wp-list-table widefat fixed media" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th scope="col" id="cb" class="manage-column column-cb check-column" style=""></th>
                                        <th scope="col" id="icon" class="manage-column column-icon" style=""></th>
                                        <th scope="col" id="title" class="manage-column column-title" style=""><span><?php print __('Downloads', PWT_PLUGIN); ?></span></th>
                                        <th scope="col" id="date" class="manage-column column-date" style=""><span><?php print __('Date', PWT_PLUGIN); ?></span></th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th scope="col" id="cb" class="manage-column column-cb check-column" style=""></th>
                                        <th scope="col" class="manage-column column-icon" style=""></th>
                                        <th scope="col" class="manage-column column-title" style=""><span><?php print __('Downloads', PWT_PLUGIN); ?></span></th>
                                        <th scope="col" class="manage-column column-date" style=""><span><?php print __('Date', PWT_PLUGIN); ?></span></th>
                                    </tr>
                                </tfoot>
                                <tbody id="the-list">
                                <?php foreach ($files AS $key => $value) : ?>
                                    <?php 
                                        $path = pathinfo($value);  
                                        $filename = $path['filename'] . (!empty($path['extension']) ? '.' . $path['extension'] : ''); 
                                    ?>
                                    <tr id="post-52" class="<?php if ($key % 2 == 0) : print 'alternate '; endif; ?>author-self status-inherit"
                                        valign="top">
                                        <th scope="row" class="check-column">
                                            <label for="cb-select-<?php print str_replace('.', '-', $filename); ?>"
                                                   class="screen-reader-text">Elige <?php print $filename; ?></label>
                                            <input type="radio" name="download" id="cb-select-<?php print str_replace('.', '-', $filename); ?>"
                                                   value="<?php print $filename; ?>"<?php if ($file && $file == $value) : print ' checked'; endif; ?> />
                                        </th>                
                                        <td class="column-icon media-icon">
                                        <?php if ($finfo) : ?>
                                            <img src="<?php print wp_mime_type_icon($finfo->file($value)); ?>" 
                                                alt="<?php print $filename; ?>" title="<?php print $filename; ?>" class="attachment-80x60" />
                                        <?php else: ?>
                                            <img src="<?php print wp_mime_type_icon('default'); ?>" 
                                                alt="<?php print $filename; ?>" title="<?php print $filename; ?>" class="attachment-80x60" />
                                        <?php endif; ?>
                                        </td>
                                        <td class="title column-title">
                                            <strong><?php print $filename; ?></strong>
                                            <p><?php print strtoupper($path['extension']); ?></p>
                                            <div class="row-actions">
                                                <span class="delete">
                                                    <a class="submitdelete" onclick="return showNotice.warn();" 
                                                        href="post.php?action=delete&amp;post=52&amp;_wpnonce=bd72250cc4">Borrar permanentemente</a>
                                                </span>
                                            </div>		
                                        </td>
                                        <td class="date column-date"><?php print date_i18n( get_option('date_format'), filectime($value) ); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>    
                        </div>
                    </div>
                </div>
            </div>
            <?php if (isset($_POST['file']) && file_exists(PWT_PLUGIN_UPLOAD_DIR . '/' . $_POST['file'])) : ?>
                <input type="hidden" name="attachment" value="<?php print $_POST['file']; ?>" />
            <?php else : ?>

            <?php endif; ?>
            <?php submit_button(__('Create payment button', PWT_PLUGIN), 'primary', 'save'); ?>
        </div>
    </form>    
</div>
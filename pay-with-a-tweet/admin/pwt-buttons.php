<?php

/**
 * pwt-buttons.php
 * 
 * Screen to manage all the available buttons
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

if ($_GET['action'] == 'delete' || $_GET['action'] == 'delete_all') {
    $button_id = intval($_GET['button']);
    check_admin_referer('delete-button_' . $button_id);

    $button = $wpdb->get_results("SELECT *  FROM " . $wpdb->prefix . "pwt_button WHERE id='" . intval($button_id) . "'");
    
    $wpdb->query($wpdb->prepare("DELETE FROM " . $wpdb->prefix . "pwt_button WHERE id = %d", $button_id));
    
    if (!empty($button) && is_array($button) && !empty($button[0]) && $_GET['action'] == 'delete_all') {

        $buttondownload = $wpdb->get_results($wpdb->prepare("SELECT COUNT(*) AS total FROM " . $wpdb->prefix . "pwt_button WHERE download='%s'", $button[0]->download));

        $buttonimage = $wpdb->get_results($wpdb->prepare("SELECT COUNT(*) AS total FROM " . $wpdb->prefix . "pwt_button WHERE image='%s'", $button[0]->image));

        if (isset($buttondownload[0]->total) && $buttondownload[0]->total == 0 
                && file_exists(PWT_PLUGIN_UPLOAD_DIR . '/' . $button[0]->download) && !empty($button[0]->download) 
                && !preg_match('#^\.(.*)$#si', $button[0]->download)
                && !is_dir(PWT_PLUGIN_UPLOAD_DIR . '/' . $button[0]->download) && $button[0]->download != PWT_PLUGIN_UPLOAD_DIR . '/index.html') {
            unlink(PWT_PLUGIN_UPLOAD_DIR . '/' . $button[0]->download);
        }

        if (isset($buttonimage[0]->total) && $buttonimage[0]->total == 0 && file_exists(PWT_PLUGIN_UPLOAD_DIR . '/images/' . $button[0]->image)
                 && !empty($button[0]->image) && !preg_match('#^\.(.*)$#si', $button[0]->image)
                && !is_dir(PWT_PLUGIN_UPLOAD_DIR . '/images/' . $button[0]->image) && $button[0]->image != 'index.html') {
            unlink(PWT_PLUGIN_UPLOAD_DIR . '/images/' . $button[0]->image);
        }
        
        $message = sprintf(__('Button %s deleted', PWT_PLUGIN), 
                $button[0]->name); 
        
        unset ($button, $buttondownload, $buttonimage);
    }
}
?>
<div class="wrap">
    <div id="icon-upload" class="icon32"><br /></div>
    <h2><?php print __("Manage your buttons", PWT_PLUGIN); ?> 
        <a href="<?php print admin_url('admin.php?page=pay-with-a-tweet/admin/pwt-create-button.php'); ?>" class="add-new-h2"><?php print __('New payment button', PWT_PLUGIN); ?></a></h2>
    <?php if ($message) : ?>
    <div id="message" class="updated"><p><?php echo $message; ?></p></div>    
    <?php endif; ?>
<?php $buttons = $wpdb->get_results("SELECT *  FROM " . $wpdb->prefix . "pwt_button"); ?>
<?php if ($buttons) : ?>
        <table class="wp-list-table widefat fixed media" cellspacing="0">
            <thead>
                <tr>
                    <th scope="col" id="cb" class="manage-column column-cb check-column" style=""></th>
                    <th scope="col" class="manage-column column-icon" style=""></th>
                    <th scope="col" id="title" class="manage-column column-title" style=""><span><?php print __('Button', PWT_PLUGIN); ?></span></th>
                    <th scope="col" id="date" class="manage-column column-date" style=""><span><?php print __('Shortcode'); ?></span></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th scope="col" id="cb" class="manage-column column-cb check-column" style=""></th>
                    <th scope="col" class="manage-column column-icon" style=""></th>
                    <th scope="col" class="manage-column column-title" style=""><span><?php print __('Button', PWT_PLUGIN); ?></span></th>
                    <th scope="col" class="manage-column column-date" style=""><span><?php print __('Shortcode'); ?></span></th>
                </tr>
            </tfoot>
            <tbody id="the-list">    
    <?php $key = 0; ?>
    <?php foreach ($buttons as $button) : ?>
                    <tr id="post-52" class="<?php if ($key % 2 == 0) : print 'alternate ';
        endif; ?> author-self status-inherit" valign="top">
                        <th scope="row" class="check-column"></th>
                        <td class="column-icon media-icon">
        <?php if (!empty($button->image) && file_exists(PWT_PLUGIN_UPLOAD_DIR . '/images/' . $button->image)) : ?>
                                <img src="<?php print PWT_PLUGIN_UPLOAD_URL . '/images/' . $button->image; ?>" 
                                     alt="<?php print $button->image; ?>" title="<?php print $button->image; ?>" class="attachment-80x60" />
                            <?php else : ?>
                                N/A
                                 <?php endif; ?>
                        </td>
                        <td class="title column-title">
                            <strong><?php print $button->name; ?></strong>
                            <p><?php print $button->message; ?></p>
                            <div class="row-actions">
                                <span class="delete">
                                    <a class="submitdelete" onclick="return showNotice.warn();"
                                       href="<?php print admin_url('admin.php?page=pay-with-a-tweet/admin/pwt-buttons.php&amp;action=delete&amp;button=' . $button->id . '&amp;_wpnonce=' . wp_create_nonce('delete-button_' . $button->id)); ?>"><?php print __('Delete'); ?></a>                                    
                                </span>
                                 | 
                                <span class="delete">
                                    <a class="submitdelete" onclick="return showNotice.warn();"
                                       href="<?php print admin_url('admin.php?page=pay-with-a-tweet/admin/pwt-buttons.php&amp;action=delete_all&amp;button=' . $button->id . '&amp;_wpnonce=' . wp_create_nonce('delete-button_' . $button->id)); ?>"><?php print __( 'Delete Permanently' ); ?></a>
                                </span>
                            </div>		
                        </td>
                        <td class="date column-date">[pwt id='<?php print $button->id; ?>']</td>
                    </tr>
        <?php $key++; ?>
    <?php endforeach; ?>
        </table>
            <?php else : ?>
        <h2>Not Found</h2>
    <?php endif; ?>
</div>
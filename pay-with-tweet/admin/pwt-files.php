<?php

/**
 * pwt-files.php
 * 
 * Screen to manage the available downloads
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

if ($_GET['action'] == 'delete') {
   
    $filename = esc_attr($_GET['filename']);
    check_admin_referer('delete-file_' . $filename);

    $buttondownload = $wpdb->get_results($wpdb->prepare("SELECT COUNT(*) AS total FROM " . $wpdb->prefix . "pwt_button WHERE download='%s'", $_GET['filename']));
    if (isset($buttondownload[0]->total) && $buttondownload[0]->total == 0 && file_exists(PWT_PLUGIN_UPLOAD_DIR . '/' . $filename) 
            && !empty($filename) && !preg_match('#^\.(.*)$#si', $filename)
            && !is_dir(PWT_PLUGIN_UPLOAD_DIR . '/' . $filename) && $filename != PWT_PLUGIN_UPLOAD_DIR . '/index.html') {
        unlink(PWT_PLUGIN_UPLOAD_DIR . '/' . $filename);

        $message = sprintf(__('File %s was be deleted', PWT_PLUGIN), $filename); 
    } else {
        $message = sprintf(__('File %s wasn\'t be deleted. Is it used by some payment button?', PWT_PLUGIN), $filename); 
    }
    
    unset($filename, $buttondownload);
}

$file = glob(PWT_PLUGIN_UPLOAD_DIR . '/*');
$finfo = (class_exists('finfo') && version_compare(PHP_VERSION, '5.3.0') >= 0) ? new finfo(FILEINFO_MIME_TYPE) : FALSE;
//check_admin_referer('bulk-media');	

?>
<div class="wrap">
    <div id="icon-upload" class="icon32"><br /></div>
    <h2><?php print __("Manage files", PWT_PLUGIN); ?> 
        <a href="<?php print admin_url('admin.php?page=pay-with-tweet/admin/pwt-upload.php'); ?>"
           class="add-new-h2"><?php print __('Upload files', PWT_PLUGIN); ?></a></h2>
    <?php if ($message) : ?>
    <div id="message" class="updated"><p><?php echo $message; ?></p></div>    
    <?php endif; ?>    
    <table class="wp-list-table widefat fixed media" cellspacing="0">
        <thead>
            <tr>
                <th scope="col" id="icon" class="manage-column column-icon" style=""></th>
                <th scope="col" id="title" class="manage-column column-title"
                    style=""><span><?php print __('Downloads', PWT_PLUGIN); ?></span>
                </th>
                <th scope="col" id="date" class="manage-column column-date" style="">
                    <span><?php print __('Date', PWT_PLUGIN); ?></span>
                </th>	
                <th scope="col" id="author" class="manage-column column-author" style=""></th>
            </tr>
        </thead>

        <tfoot>
            <tr>
                <th scope="col" class="manage-column column-icon" style=""></th>
                <th scope="col" class="manage-column column-title" style="">
                    <span><?php print __('Downloads', PWT_PLUGIN); ?></span>
                <th scope="col" class="manage-column column-date" style="">
                    <span><?php print __('Date', PWT_PLUGIN); ?></span>
                </th>
                <th scope="col" class="manage-column columd-author" style=""></th>
            </tr>
        </tfoot>

        <tbody id="the-list">
        <?php foreach ($file AS $key => $value) : ?>
            <?php 
            
                if (is_dir($value) || $value == PWT_PLUGIN_UPLOAD_DIR . '/index.html') :
                    continue;
                endif;
                $path = pathinfo($value);  
                $filename = $path['filename'] . (!empty($path['extension']) ? '.' . $path['extension'] : ''); 
            ?>
            <tr id="post-52" class="<?php if ($key % 2 == 0) : print 'alternate '; endif; ?>author-self status-inherit" valign="top">
                <td class="column-icon media-icon">
                    <?php if ($finfo) : ?>
                        <a href="#" onclick="jQuery('#form-payment-button-<?php print $key; ?>').submit();"><img 
                                src="<?php print wp_mime_type_icon($finfo->file($value)); ?>" alt="" title=""
                                class="attachment-80x60" /></a>
                    <?php else: ?>
                        <a href="#" onclick="jQuery('#form-payment-button-<?php print $key; ?>').submit();"><img 
                                src="<?php print wp_mime_type_icon('default'); ?>" alt="" title="" 
                                class="attachment-80x60" /></a>
                    <?php endif; ?>
                </td>
                <td class="title column-title">
                    <strong><?php print $filename; ?></strong>
                    <p><?php print strtoupper($path['extension']); ?></p>
                    <div class="row-actions">
                        <span class="delete">
                            <a class="submitdelete" onclick="return showNotice.warn();" 
                               href="<?php print admin_url('admin.php?page=pay-with-tweet/admin/pwt-files.php&amp;action=delete&amp;filename=' . $filename . '&amp;_wpnonce=' . wp_create_nonce('delete-file_' . $filename)); ?>"><?php print __( 'Delete Permanently' ); ?></a> | 
                        </span>
                        <span class="view">
                            <a href="#" onclick="jQuery('#form-payment-button-<?php print $key; ?>').submit();"><?php 
                                print __('Create payment button', PWT_PLUGIN); ?></a>
                        </span>
                    </div>		
                </td>
                <td class="date column-date"><?php print date_i18n( get_option('date_format'), filectime($value) ); ?></td>
                <td class="author column-author">
                    <form method="post" id="form-payment-button-<?php print $key; ?>" action="<?php print admin_url('admin.php?page=pay-with-tweet/admin/pwt-create-button.php'); ?>">
                        <?php wp_nonce_field(); ?>
                        <input type="hidden" name="file" value="<?php print $filename; ?>" />
                        <?php submit_button( __('Create payment button', PWT_PLUGIN), 'action', 'save' ); ?>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>    

</div>
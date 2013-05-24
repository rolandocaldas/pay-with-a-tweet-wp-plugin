<?php

/**
 * shortcode.php
 * 
 * All the functions to add the shortcode support
 * 
 * @author Rolando Caldas Sánchez <rolando.caldas@gmail.com>
 * @package Pay With a Tweet
 * @subpackage shortcodes
 * @version 1.0
 * @since 1.0
 * @copyright (c) 2013, Rolando Caldas
 * @license http://opensource.org/licenses/GPL-2.0 GPL2
 * @filesource
 */

/**
 * pwt_shortcode_func()
 * 
 * Support to WordPress shortcode for payment buttons
 * [pwt id="value"]
 * 
 * @version 1.0
 * @since 1.0
 * @global Object $wpdb WP Object to interact with database
 * @param Array $atts array("id" => Button Id)
 * @return string Payment button HTML
 */
function pwt_shortcode_func($atts) {
    global $wpdb;

    $return = NULL;
    
    $shortcode = $wpdb->get_results("SELECT *  FROM " . $wpdb->prefix . "pwt_button WHERE id='" . intval($atts['id']) . "'");
    if (!empty($shortcode) && is_array($shortcode) && !empty($shortcode[0])) {
        $return = '<a href="' . get_option('siteurl') . '/' . PWT_PLUGIN . '/download/?id=' . $shortcode[0]->id . '&amp;action=get_access" target="_blank">';
        if (!empty($shortcode[0]->image) && file_exists(PWT_PLUGIN_UPLOAD_DIR . '/images/' . $shortcode[0]->image)) {
            $return .= '<img src="' . PWT_PLUGIN_UPLOAD_URL . '/images/' . $shortcode[0]->image . '" 
                                alt="' . sprintf(__('Pay with a Tweet to download %s', PWT_PLUGIN), $shortcode[0]->name) . '"
                                title="' . sprintf(__('Pay with a Tweet to download %s', PWT_PLUGIN), $shortcode[0]->name) . '" />';
        } else {
            $return .= sprintf(__('Download %s',PWT_PLUGIN), $shortcode[0]->name);
        }
        $return .= '</a>';
    }
    
    return $return;
}

add_shortcode('pwt', 'pwt_shortcode_func');

<?php

/**
 * action.php
 * 
 * File that contains all the functions to use in action hooks
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
 * pwt_admin
 * 
 * Declare admin pages
 */
function pwt_admin() {

    $parent = 'pay-with-tweet/admin/pwt-buttons.php';
    $parent_add = 'pay-with-tweet/admin/pwt-create-button.php';
    $parent_upload = 'pay-with-tweet/admin/pwt-upload.php';
    $parent_files = 'pay-with-tweet/admin/pwt-files.php';
    $parent_config = 'pay-with-tweet/admin/pwt-config.php';

    add_object_page(__('Pay with a Tweet', PWT_PLUGIN), __('Pay with a Tweet', PWT_PLUGIN), 0, $parent, '', PWT_PLUGIN_URL . 'img/twitter.png');

    add_submenu_page($parent, __("Manage your buttons", PWT_PLUGIN), __("Manage your buttons", PWT_PLUGIN), 0, $parent);
    add_submenu_page($parent, __('New payment button', PWT_PLUGIN), __('New payment button', PWT_PLUGIN), 0, $parent_add);
    add_submenu_page($parent, __('Manage files', PWT_PLUGIN), __('Manage files', PWT_PLUGIN), 0, $parent_files);
    add_submenu_page($parent, __('Upload files', PWT_PLUGIN), __('Upload files', PWT_PLUGIN), 0, $parent_upload);
    add_submenu_page($parent, __('Configuration', PWT_PLUGIN), __('Configuration', PWT_PLUGIN), 0, $parent_config);
}

/**
 * pwt_wp_enqueue_scripts
 * 
 * Enqueue plugin style-file
 */
function pwt_wp_enqueue_scripts() {
    wp_enqueue_script('pwt-script', PWT_PLUGIN_URL . '/js/pwt.js', array('jquery'));
    wp_enqueue_style('pwt-style', PWT_PLUGIN_URL . '/css/style.css');
}

/**
 * pwt_admin_init
 * 
 * Admin init actions
 */
function pwt_admin_init() {
    add_action('admin_enqueue_scripts', 'pwt_wp_enqueue_scripts');    
}

/**
 * pwt_plugin_init
 * 
 * Register plugin actions
 */
function pwt_plugin_init() {
    add_action('admin_menu', 'pwt_admin');
    add_action('admin_init', 'pwt_admin_init');
}


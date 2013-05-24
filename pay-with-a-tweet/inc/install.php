<?php

/**
 * install.php
 * 
 * Installation functions
 * 
 * @author Rolando Caldas Sánchez <rolando.caldas@gmail.com>
 * @package Pay With a Tweet
 * @subpackage installation
 * @version 1.0
 * @since 1.0
 * @copyright (c) 2013, Rolando Caldas
 * @license http://opensource.org/licenses/GPL-2.0 GPL2
 * @filesource
 */

/**
 * pwt_install
 * 
 * Create the database tables, upload folders and configuration values
 * 
 * @version 1.0
 * @since 1.0
 * @global Object $wpdb WP Object to interact with database
 */
function pwt_install() {
    global $wpdb;

    if (!is_dir(PWT_PLUGIN_UPLOAD_DIR)) {
        @mkdir(PWT_PLUGIN_UPLOAD_DIR, 0777, true);
        @mkdir(PWT_PLUGIN_UPLOAD_DIR . '/images', 0777, true);
    }
    @file_put_contents(PWT_PLUGIN_UPLOAD_DIR . '/.htaccess', "deny from all\n");
    @file_put_contents(PWT_PLUGIN_UPLOAD_DIR . '/index.html', "");
    @file_put_contents(PWT_PLUGIN_UPLOAD_DIR . '/images/index.html', "");
    @file_put_contents(PWT_PLUGIN_UPLOAD_DIR . '/images/.htaccess', "allow from all\n");

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    $table_name = $wpdb->prefix . "pwt_button";

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id int(10) NOT NULL AUTO_INCREMENT,
        time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        name VARCHAR(255) DEFAULT '' NOT NULL,
        message VARCHAR(255) DEFAULT '' NOT NULL,
        download VARCHAR(255) DEFAULT '' NOT NULL,
        image VARCHAR(255) DEFAULT '' NOT NULL,
        PRIMARY KEY id (id)
    );";

    $sql2 = "CREATE TABLE IF NOT EXISTS " . $table_name . "_stats (
        id int(10) NOT NULL AUTO_INCREMENT,
        bid int(10) NOT NULL,
        time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        name VARCHAR(255) DEFAULT '' NOT NULL,
        PRIMARY KEY id (id),
        KEY bid (bid)
    );";
    
    dbDelta($sql);
    dbDelta($sql2);

    add_option(PWT_PLUGIN . "_db_version", PWT_PLUGIN_VERSION);
    add_option(PWT_PLUGIN . '_twitter_consumerkey', '');
    add_option(PWT_PLUGIN . '_twitter_consumersecret', '');
}
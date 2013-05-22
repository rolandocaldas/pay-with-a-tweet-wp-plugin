<?php
/*
  Plugin Name: Pay With a Tweet
  Plugin URI: http://rolandocaldas.com/proyectos/wordpress/pay-with-a-tweet
  Description: Create buttons so that your visitors can download files after posting on his twitter the message you choose. You can display different buttons using shortcodes or the widget included. <a href="http://rolandocaldas.com/proyectos/wordpress/pay-with-a-tweet" target="_blank">Documentation</a>
  Version: 1.0
  Author: Rolando Caldas Sánchez
  Author URI: http://rolandocaldas.com/
  License: License: GPL2

  Copyright 2013 Rolando Caldas Sánchez (rolando.caldas@gmail.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * pay-with-tweet.php
 * 
 * File that load all the info and options of the pay with a tweet plugin
 * 
 * @author Rolando Caldas Sánchez <rolando.caldas@gmail.com>
 * @package Pay With a Tweet
 * @version 1.0
 * @since 1.0
 * @copyright (c) 2013, Rolando Caldas
 * @license http://opensource.org/licenses/GPL-2.0 GPL2
 * @filesource
 */

/* define plugin constants */
define('PWT_PLUGIN', 'pay-with-tweet');
define('PWT_PLUGIN_VERSION', '1.0');
define('PWT_PLUGIN_POSITION', '6.76');
define('PWT_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('PWT_PLUGIN_URL', plugin_dir_url(__FILE__));
define('PWT_PLUGIN_UPLOAD_DIR', WP_CONTENT_DIR . '/uploads/pay-with-tweet');
define('PWT_PLUGIN_UPLOAD_URL', WP_CONTENT_URL . '/uploads/pay-with-tweet');

/* load language */
load_plugin_textdomain(PWT_PLUGIN, false, PWT_PLUGIN . '/languages');

/* include plugin files*/
include_once(PWT_PLUGIN_PATH . 'inc/action.php');
include_once(PWT_PLUGIN_PATH . 'inc/filter.php');
include_once(PWT_PLUGIN_PATH . 'inc/functions.php');
include_once(PWT_PLUGIN_PATH . 'inc/widget.php');
include_once(PWT_PLUGIN_PATH . 'inc/shortcode.php');
include_once(PWT_PLUGIN_PATH . 'inc/install.php');

/* install hook plugin */
register_activation_hook( __FILE__, 'pwt_install' );

/* start plugin */
add_action('init', 'pwt_plugin_init');


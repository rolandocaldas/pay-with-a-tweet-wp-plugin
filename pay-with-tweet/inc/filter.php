<?php

/**
 * 
 * filter.php
 * 
 * File that contains all the functions to use in filter hooks
 *  
 * @author Rolando Caldas Sánchez <rolando.caldas@gmail.com>
 * @package Pay With a Tweet
 * @subpackage hooks
 * @version 1.0
 * @copyright (c) 2013, Rolando Caldas
 * @license http://opensource.org/licenses/GPL-2.0 GPL2
 * @filesource
 */

/**
 * Configure the folder to upload the downloabled files.
 * 
 * @return array
 */
function pwt_upload_dir() {
 
    $args = array();
    $args['path'] = PWT_PLUGIN_UPLOAD_DIR;
    $args['url']  = PWT_PLUGIN_UPLOAD_URL;
    $args['basedir'] = PWT_PLUGIN_UPLOAD_DIR;
    $args['baseurl'] = PWT_PLUGIN_UPLOAD_URL;
    $args['error'] = false;

    return $args;
}

/**
 * Conifgure the folder to upload the images files.
 * 
 * @return array
 */
function pwt_upload_dir_image() {
 
    $args = array(
        'path' => PWT_PLUGIN_UPLOAD_DIR . '/images/',
        'url'  => PWT_PLUGIN_UPLOAD_URL . '/images/',
        'basedir' => PWT_PLUGIN_UPLOAD_DIR . '/images/',
        'baseurl' => PWT_PLUGIN_UPLOAD_URL . '/images/',
        'error' => false
    );

    return $args;
}

/**
 * Download error page
 */
function pwt_download_404_template() {
    get_header(); ?>

	<div id="container">
		<div id="content" role="main">

			<div id="post-0" class="post error404 not-found">
				<h1 class="entry-title">Error <?php print $GLOBALS['pwt_error']->http_code; ?></h1>
				<div class="entry-content">
                                    <?php print $GLOBALS['pwt_error']->http_header['status']; ?>
				</div><!-- .entry-content -->
			</div><!-- #post-0 -->

		</div><!-- #content -->
	</div><!-- #container -->
	<script type="text/javascript">
		// focus on search field after it has loaded
		document.getElementById('s') && document.getElementById('s').focus();
	</script>
        <?php
        
        get_footer();
}
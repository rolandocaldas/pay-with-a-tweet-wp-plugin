<?php

/**
 * widget.php
 * 
 * File that contains the widgets code
 * 
 * @author Rolando Caldas Sánchez <rolando.caldas@gmail.com>
 * @package Pay With a Tweet
 * @subpackage Widgets
 * @version 1.0
 * @since 1.0
 * @copyright (c) 2013, Rolando Caldas
 * @license http://opensource.org/licenses/GPL-2.0 GPL2
 * @filesource
 */

/**
 * Register the widget for use in Appearance -> Widgets
 */
add_action('widgets_init', 'pwt_widgets_init');

/**
 * pwt_widgets_init
 * 
 * Register the Pwt_Widget to use in WordPress Sidebars
 * 
 * @version 1.0
 * @since 1.0
 */
function pwt_widgets_init() {
    register_widget('Pwt_Widget');
}

/**
 * Pwt_Widget
 * 
 * Class to show a payment button in a sidebar like a widget
 * 
 * @version 1.0
 * @since 1.0
 */
class Pwt_Widget extends WP_Widget {

    /**
     * Pwt_Widget()
     * 
     * Constructor of Pwt_Widget widget.
     */
    function Pwt_Widget() {
        parent::WP_Widget(false, __('Pay with a Tweet',PWT_PLUGIN));
    }
    
    /**
     * get_shortcodes()
     * 
     * Method to select all the available payment buttons
     * 
     * @global Object $wpdb
     * @return Array Return the available buttons in an array of objects 
     */
    function get_shortcodes() {
        global $wpdb; 
        return $wpdb->get_results("SELECT *  FROM " . $wpdb->prefix . "pwt_button");
    }
    
    /**
     * get_shortcode()
     * 
     * Method to find and return one payment button.
     * 
     * @global Object $wpdb
     * @param int $id
     * @return Array array('0' => Object)
     */
    function get_shortcode($id) {
        global $wpdb; 
        return $wpdb->get_results("SELECT *  FROM " . $wpdb->prefix . "pwt_button WHERE id='" . intval($id) . "'");
    }

    /**
     * form()
     * 
     * Overrided method to configure the button.
     * 
     * @param Array $instance
     */
    function form($instance) {
        $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '' ) );
        $title = strip_tags($instance['title']);
        $selected = intval($instance['shortcode']);
        $text = esc_textarea($instance['text']);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php print __( 'Title' ); ?>:
                <input type="text" name="<?php echo $this->get_field_name( 'title' ); ?>" id="<?php echo $this->get_field_id( 'title' ); ?>"
                       value="<?php print $title; ?>" class="widefat" />
            </label>
	</p>        
        <p>
            <?php print __('Text'); ?>:            
            <textarea class="widefat" rows="4" cols="20" id="<?php echo $this->get_field_id('text'); ?>" 
                      name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>
        </p>
        <p>
            <?php print __('Download', PWT_PLUGIN); ?>:
            <select name="<?php echo $this->get_field_name( 'shortcode' ); ?>" id="<?php echo $this->get_field_id( 'shortcode' ); ?>">
            <?php foreach ($this->get_shortcodes() AS $shortcode) { ?>
                <option value="<?php print $shortcode->id; ?>"<?php if ($shortcode->id == $selected) { echo ' selected'; } ?>><?php print $shortcode->name; ?></option>
            <?php } ?>
            </select>
	</p>
        <?php
    }

    /**
     * update()
     * 
     * @param type $new_instance
     * @param type $old_instance
     * @return type
     */
    function update($new_instance, $old_instance) {
        // processes widget options to be saved
        return $new_instance;
    }

    /**
     * widget
     * 
     * Overrided method to print the button 
     * 
     * @param Array $args
     * @param Array $instance
     */
    function widget($args, $instance) {
		extract($args);
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$text = apply_filters( 'widget_text', empty( $instance['text'] ) ? '' : $instance['text'], $instance );
                $shortcode = $this->get_shortcode($instance['shortcode']);
                
		echo $before_widget;
		if ( !empty( $title ) ) { echo $before_title . $title . $after_title; } ?>
                    <div class="textwidget">
                        <?php echo $text; ?>
                    <?php if (!empty($shortcode)) { ?>
                        <?php if (!empty($shortcode[0]->image) && file_exists(PWT_PLUGIN_UPLOAD_DIR . '/images/' . $shortcode[0]->image)) { ?>
                        <a href="<?php print get_option('siteurl'); ?>/<?php print PWT_PLUGIN; ?>/download/?id=<?php print $shortcode[0]->id; ?>&amp;action=get_access" target="_blank"><img 
                                src="<?php print PWT_PLUGIN_UPLOAD_URL . '/images/' . $shortcode[0]->image ; ?>" 
                                alt="<?php print sprintf(__('Download %s', PWT_PLUGIN), $shortcode[0]->name); ?>"
                                title="<?php print sprintf(__('Download %s', PWT_PLUGIN), $shortcode[0]->name); ?>" /></a>
                        <?php } else { ?>
                        <a href="<?php print get_option('siteurl'); ?>/<?php print PWT_PLUGIN; ?>/download/?id=<?php print $shortcode[0]->id; ?>&amp;action=get_access" 
                           target="_blank"><?php print sprintf(__('Download %s', PWT_PLUGIN), $shortcode[0]->name); ?></a>
                        <?php } ?>
                    <?php } ?>
                    </div>
		<?php
		echo $after_widget;
    }

}

?>
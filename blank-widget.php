<?php
/*
Plugin Name: Blank widget
Plugin URI: [Enter your website URL]
Description: [Enter brief description of plugin]
Version: 1.0
Author: Allen Tullett
Author URI: http://www.allentullett.co.uk/
*/

add_action( 'widgets_init', 'load_widgets' );

function load_widgets() {
    register_widget( 'blank_widget' );
}

class blank_widget extends WP_Widget {
 
    var $textdomain;
   
    // Widget name
    function Widget_case_study() {
        parent::WP_Widget(false, $name = __('Case study', 'case_study') );
	
	// This is where we add the style and script
        add_action('load-widgets.php', array(&$this, 'colour_picker_scripts') );
	add_action('admin_enqueue_scripts', array($this, 'upload_scripts'));
	
    }
    
    // Enqueue wordpress colour picker scripts
    function colour_picker_scripts() {    
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
    }
     
    // Upload the Javascripts for the media uploader
    function upload_scripts() {
        wp_enqueue_script('media-upload');
        wp_enqueue_script('thickbox');
        wp_enqueue_script('upload_media_widget', plugin_dir_url(__FILE__) . 'upload-media.js', array('jquery'));
        wp_enqueue_style('thickbox');
    }

    // Creates the widget form
    function form($instance) {	
	$text_field = esc_attr($instance['text_field']);
	$text_area = esc_attr($instance['text_area']);
	$number_field = esc_attr($instance['number_field']);
	$checkbox = esc_attr($instance['checkbox']);
	$radio_buttons = esc_attr($instance['radio_buttons']);
	$category = esc_attr($instance['category']);
	
	$image = '';
        if(isset($instance['image'])) {$image = $instance['image'];}
	
	$defaults = array(
            'colour' => '#ff0000'
        );

        // Merge the user-selected arguments with the defaults
        $instance = wp_parse_args( (array) $instance, $defaults ); ?>
        <script type='text/javascript'>
            jQuery(document).ready(function($) {
                $('.colour-picker').wpColorPicker();
            });
        </script>
    
	    <p>
		<label for="<?php echo $this->get_field_id('text_field'); ?>">
		    <?php echo('Text field'); ?>
		</label> 
		<input class="widefat" id="<?php echo $this->get_field_id('text_field'); ?>" name="<?php echo $this->get_field_name('text_field'); ?>" type="text" value="<?php echo $text_field; ?>" />
	    </p>
	    <p>
		<label for="<?php echo $this->get_field_id('text_area'); ?>">
		    <?php echo('Text area'); ?>
		</label>
		<textarea class="widefat" id="<?php echo $this->get_field_id('text_area'); ?>" name="<?php echo $this->get_field_name('text_area'); ?>" value="<?php echo $text_area; ?>" rows="5"><?php if (!empty($text_area)) echo $text_area; ?></textarea>
	    </p>
	    <p>
		<label for="<?php echo $this->get_field_id('text_area'); ?>">
		    <?php echo('Numerical'); ?>
		</label>
		<input class="widefat" id="<?php echo $this->get_field_id('number_field'); ?>" name="<?php echo $this->get_field_name('number_field'); ?>" type="number" value="<?php echo $number_field; ?>" />
	    </p>
	    <p>
		<label for="<?php echo $this->get_field_id('checkbox'); ?>">
		    <?php echo('First paragrph bold'); ?>
		</label>
		<input class="checkbox" type="checkbox" <?php checked($instance['checkbox'], 'on'); ?> id="<?php echo $this->get_field_id('checkbox'); ?>" name="<?php echo $this->get_field_name('checkbox'); ?>" />	
	    </p>
	    <p>
		<label for="<?php echo $this->get_field_id('text_area'); ?>">
		    <?php echo('Radio buttons'); ?>
		</label><br>
		<label for="<?php echo $this->get_field_id('radio_buttons'); ?>">
		    <?php _e('Option 1:'); ?>
		    <input class="" id="<?php echo $this->get_field_id('radio_option_1'); ?>" name="<?php echo $this->get_field_name('radio_buttons'); ?>" type="radio" value="radio_option_1" <?php if($radio_buttons === 'radio_option_1'){ echo 'checked="checked"'; } ?> />
		</label><br>
		<label for="<?php echo $this->get_field_id('radio_buttons'); ?>">
		    <?php _e('Option 2:'); ?>
		    <input class="" id="<?php echo $this->get_field_id('radio_option_2'); ?>" name="<?php echo $this->get_field_name('radio_buttons'); ?>" type="radio" value="radio_option_2" <?php if($radio_buttons === 'radio_option_2'){ echo 'checked="checked"'; } ?> />
		</label>
	    </p>
	    <p>
		<label for="<?php echo $this->get_field_id('category'); ?>">
		    <?php echo('Slide category'); ?>
		</label><br>
		<select id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>" class="widefat" style="width:100%;">
		    <?php foreach(get_terms('slides_categories','parent=0&hide_empty=0') as $term) { ?>
		    <option <?php selected( $instance['category'], $term->term_id ); ?> value="<?php echo $term->term_id; ?>"><?php echo $term->name; ?></option>
		    <?php } ?>      
		</select>
	    </p>
	    <p>
	        <label for="<?php echo $this->get_field_name( 'image' ); ?>"><?php _e( 'Media upload tool:' ); ?></label>
	        <input name="<?php echo $this->get_field_name( 'image' ); ?>" id="<?php echo $this->get_field_id( 'image' ); ?>" class="widefat" type="text" size="36"  value="<?php echo esc_url( $image ); ?>" />
	        <input class="upload_image_button" type="button" value="Upload" /><br><br>
		<img src="<?php echo esc_url( $image ); ?>" style="width:100%; height:auto;"/>
	    </p>
	    <p>
		<label for="<?php echo $this->get_field_id( 'colour' ); ?>"><?php _e( 'Colour range selector', $this->textdomain ); ?></label><br>
		<input class="colour-picker" type="text" id="<?php echo $this->get_field_id( 'colour' ); ?>" name="<?php echo $this->get_field_name( 'colour' ); ?>" value="<?php echo esc_attr( $instance['colour'] ); ?>" />                            
	    </p>
        <?php 
    }
    
    // Update the database
    function update($new_instance, $old_instance) {
	$instance = $old_instance;
	$instance['text_field'] = strip_tags($new_instance['text_field']);
	$instance['text_area'] = esc_textarea($new_instance['text_area']);
	$instance['number_field'] = strip_tags($new_instance['number_field']);
	$instance['checkbox'] = $new_instance['checkbox'];
	$instance['radio_buttons'] = strip_tags($new_instance['radio_buttons']);
	$instance['category'] = strip_tags( $new_instance['category'] );
	$instance['image'] = $new_instance['image'];
	$instance['colour'] = $new_instance['colour'];
        return $instance;
    }
    
    // Defines the output of the widget
    function widget($args, $instance) {	
        extract( $args );
        $text_field = $instance['text_field'];
        $text_area = $instance['text_area'];
        $number_field = $instance['number_field'];
	$checkbox = $instance['checkbox'] ? 'true' : 'false';
        $radio_buttons = $instance['radio_buttons'];
        $category = $instance['category'];
	$image = $instance['image'];
	$colourpicker = $instance['colour'];
	?>
            <?php echo $before_widget; ?>
		<p>Text field: <?php echo $text_field; ?></p>
		<p>Text area:<br><?php echo $text_area; ?></p>
		<p>Number field: <?php echo $number_field; ?></p>
		<p>Checkbox: <?php if('on' == $instance['checkbox'] ) { ?>On<?php } ?></p>
		<p>Radio button: <?php echo $radio_buttons; ?></p>
		<p>Drop down category: <?php echo $category; ?></p>
		<p>Image: <?php echo $image;?></p>
		<p>Colour: <?php echo $colourpicker;?></p>
            <?php echo $after_widget; ?>
        <?php
    }
}
?>
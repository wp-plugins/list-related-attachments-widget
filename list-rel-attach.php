<?php
/*
	Plugin Name: List Related Attachments
	Plugin URI: http://plugins.twinpictures.de/plugins/list-related-attachments/
	Description: Display a filtered list of all related attachments linked to the current post or page
	Version: 1.8
	Author: Twinpictures
	Author URI: http://www.twinpictures.de/
	License: GPL2
*/

/*  Copyright 2012 Twinpictures (www.twinpictures.de)

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

class listAttach extends WP_Widget {
    /** constructor */
    function listAttach() {
		$widget_ops = array('classname' => 'listAttach', 'description' => __('Display a filtered list of related attachments for the current post') );
		$this->WP_Widget('listAttach', 'List Related Attachments', $widget_ops);
    }
	
    /** Widget */
    function widget($args, $instance) {
		global $wpdb, $post;
		extract($args);
		$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
		$count = empty($instance['count']) ? '-1' : apply_filters('widget_count', $instance['count']);
		$type = empty($instance['type']) ? 'application' : apply_filters('widget_type', $instance['type']);
		$orderby = empty($instance['orderby']) ? 'date' : apply_filters('widget_orderby', $instance['orderby']);
		$order = empty($instance['order']) ? 'DESC' : apply_filters('widget_order', $instance['order']);
		$display = empty($instance['display']) ? 'title' : apply_filters('widget_display', $instance['display']);
		$target = empty($instance['target']) ? 'self' : apply_filters('widget_target', $instance['target']);
		
		$args = array(
			'post_type' => 'attachment',
			'post_mime_type' => $type,
			'numberposts' => $count,
			'orderby' => $orderby,
			'order' => $order,
			'post_parent' => $post->ID
		);
		
		$attachments = get_children($args);
		if ($attachments) {
			echo $before_widget;
			if($title){
				echo $before_title . $title . $after_title;
			}
			echo '<ul>';
			$link_target = '';
			if($target != 'self'){
				$link_target = 'target="_blank"';
			}
			foreach ($attachments as $attachment) {
				$mime_parts = explode("/", $attachment-> post_mime_type);
				$mime_class = 'class="mime-'.$mime_parts[count($mime_parts)-1].'"';
				//replace title
				$display_str = str_replace('title', $attachment->post_title, $display);
				//replace caption
				$display_str = str_replace('caption', $attachment->post_excerpt, $display_str);
				//replace description
				$display_str = str_replace('description', $attachment->post_content, $display_str);
				echo '<li '.$mime_class.'><a href="'.wp_get_attachment_url($attachment->ID).'" '.$link_target.'>'.$display_str.'</a></li>';     
			}
			echo '</ul>'.$after_widget;
		}
	}
	
	/** Update */
    function update($new_instance, $old_instance) {
		$instance = array_merge($old_instance, $new_instance);
		return array_map('mysql_real_escape_string', $instance);
    }
	
	function form($instance) {
		$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
		$count = empty($instance['count']) ? '-1' : apply_filters('widget_count', $instance['count']);
		$type = empty($instance['type']) ? 'application' : apply_filters('widget_type', $instance['type']);
		$orderby = empty($instance['orderby']) ? 'date' : apply_filters('widget_orderby', $instance['orderby']);
		$order = empty($instance['order']) ? 'DESC' : apply_filters('widget_order', $instance['order']);
		$display = empty($instance['display']) ? 'title' : apply_filters('widget_display', $instance['display']);
		$target = empty($instance['target']) ? 'self' : apply_filters('widget_target', $instance['target']);
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Count:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo $count; ?>" /></label>
		<span class="description"><?php _e('(-1 = all)'); ?></span></p>
		<p><label for="<?php echo $this->get_field_id('type'); ?>"><?php _e('Attachment Mime/Type:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('type'); ?>" name="<?php echo $this->get_field_name('type'); ?>" type="text" value="<?php echo $type; ?>" /></label>
		<a class="description" href="http://en.wikipedia.org/wiki/MIME_type#List_of_common_media_types" target="_blank"><?php _e('list of common mime/types'); ?></a></p>
		<p><label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Order By:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" type="text" value="<?php echo $orderby; ?>" /></label>
		<a class="description" href="http://codex.wordpress.org/Function_Reference/query_posts#Orderby_Parameters" target="_blank"><?php _e('list of orderby values'); ?></a></p>
		<p><label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('Order Direction:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>" type="text" value="<?php echo $order; ?>" /></label>
		<span class="description"><?php _e('Valid values: ASC or DESC'); ?></span></p>
		<p><label for="<?php echo $this->get_field_id('display'); ?>"><?php _e('Display:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('display'); ?>" name="<?php echo $this->get_field_name('display'); ?>" type="text" value="<?php echo $display; ?>" /></label>
		<span class="description"><?php _e('title, caption and/or descripton'); ?></span></p>
        <p><?php _e('Target:'); ?> <select name="<?php echo $this->get_field_name('target'); ?>" id="<?php echo $this->get_field_name('target'); ?>">
		<?php	
		    $option_arr = array('self', 'blank');
			foreach($option_arr AS $opt){
				$selected = '';
				if($target == $opt){
					$selected = 'SELECTED';
				}
				echo '<option value="'.$opt.'" '.$selected.'>'.$opt.'</option>';
			}
		?>
	    </select></p>
		<?php
	}
} // class listAttach

// register listAttach widget
add_action('widgets_init', create_function('', 'return register_widget("listAttach");'));

//the short code
function listattach($atts) {
	global $wpdb, $post;
                
    extract(shortcode_atts(array(
		'type' => 'application',
		'count' => -1,
		'orderby' => 'date',
		'order' => 'DESC',
		'show' => 'title',
		'target' => 'self'
	), $atts));
	
	$args = array(
		'post_type' => 'attachment',
		'post_mime_type' => $type,
		'numberposts' => $count,
		'orderby' => $orderby,
		'order' => $order,
		'post_parent' => $post->ID
	);
	
	$attachments = get_children($args);
	if ($attachments) {
		$lra = '<ul class = "list-related-attach '.$show.'">';
		$link_target = '';
		if($target != 'self'){
			$link_target = 'target="_blank"';
		}
		foreach ($attachments as $attachment) {
			//let's add swill's sexy mime type class, shall we?
			$mime_parts = explode("/", $attachment->post_mime_type);
            $mime_class = 'class="mime-'.$mime_parts[count($mime_parts)-1].'"';
			$descr = 'post_title';
			//replace title
			$display_str = str_replace('title', $attachment->post_title, $show);
			//replace caption
			$display_str = str_replace('caption', $attachment->post_excerpt, $display_str);
			//replace description
			$display_str = str_replace('description', $attachment->post_content, $display_str);
			$lra .= '<li '.$mime_class.'><a href="'.wp_get_attachment_url($attachment->ID).'" '.$link_target.'>'.$display_str.'</a></li>'; 
		}
		wp_reset_postdata();
		$lra .= '</ul>';
	}
	return $lra;
}
add_shortcode('list-related-attach', 'listattach');

?>
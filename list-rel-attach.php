<?php
/*
	Plugin Name: List Related Attachments Widget
	Plugin URI: http://www.twinpictures.de/related-attachments/
	Description: Display a filtered list of related attachments linked to the current post or page
	Version: 1.6
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

//the widget
function widget_listattach_init() {

	if ( !function_exists('register_sidebar_widget') )
		return;
		
	function sanitize_attachment($name) {
    	$name = strtolower($name); // all lowercase
    	$name = preg_replace('/[^a-z0-9 ]/','', $name); // nothing but a-z 0-9 and spaces
    	$name = preg_replace('/\s+/','-', $name); // spaces become hyphens
    	return $name;
  	}

	// Options and default values for this widget
	function widget_listattach_options() {
		return array(
			'title' => 'Attachments',
			'count' => -1,
			'type' => 'application',
			'orderby' => 'date',
			'order' => 'DESC',
			'display' => 'title'
		);
	}

	function widget_listattach($args) {
		global $wpdb, $post;

		extract($args);
		$options = array_merge(widget_listattach_options(), get_option('widget_listattach'));
		unset($options[0]); //returned by get_option(), but we don't need it

		$args = array(
			'post_type' => 'attachment',
			'post_mime_type' => $options['type'],
			'numberposts' => $options['count'],
			'orderby' => $options['orderby'],
			'order' => $options['order'],
			'post_parent' => $post->ID
		);
		
		$attachments = get_children($args);
		if ($attachments) {
			
			echo $before_widget . $before_title . $options['title'] . $after_title;
			echo '<ul>';
			foreach ($attachments as $attachment) {
				$mime_parts = explode("/", $attachment-> post_mime_type);
				$mime_class = 'class="mime-'.$mime_parts[count($mime_parts)-1].'"';
				$descr = 'post_title'; 
				if($options['display'] == 'caption'){
					$descr = 'post_excerpt';
				}
				else if($options['display'] == 'description'){
					$descr = 'post_content';  
				}
				if($attachment->$descr){
					echo '<li '.$mime_class.'><a href="'.wp_get_attachment_url($attachment->ID).'">'.$attachment->$descr.'</a></li>';     
				}
				else{
					echo '<li '.$mime_class.'>'.wp_get_attachment_link($attachment->ID).'</li>';
				}
			}
			echo '</ul>'.$after_widget;
		}
		
	}

	// This is the function that outputs the form to let the users edit
	// the widget's title. It's an optional feature that users cry for.
	function widget_listattach_control() {
		// Each widget can store and retrieve its own options.
		// Here we retrieve any options that may have been set by the user
		// relying on widget defaults to fill the gaps.
		if(($options = get_option('widget_listattach')) === FALSE) $options = array();

		$options = array_merge(widget_listattach_options(), $options);
		unset($options[0]); //returned by get_option(), but we don't need it

		// If user is submitting custom option values for this widget
		if ( $_POST['listattach-submit'] ) {
			// Remember to sanitize and format use input appropriately.
			foreach($options as $key => $value)
				if(array_key_exists('listattach-'.sanitize_attachment($key), $_POST))
				$options[$key] = strip_tags(stripslashes($_POST['listattach-'.sanitize_attachment($key)]));

			// Save changes
			update_option('widget_listattach', $options);
		}
		
		// title option
		echo '<p style="text-align:left"><label for="listattach-title">Title: <input style="width: 200px;" id="listattach-title" name="listattach-title" type="text" value="'.$options['title'].'" /></label></p>';
		// count option
		echo '<p style="text-align:left"><label for="listattach-count">Count: <input style="width: 70px;" id="listattach-count" name="listattach-count" type="text" value="'.$options['count'].'" /></label> (-1 = all)</p>';

		// type option
		echo '<p style="text-align:left"><label for="listattach-type">Attachment Mime/Type: <input style="width: 200px;" id="listattach-type" name="listattach-type" type="text" value="'.$options['type'].'" /></label><br/>';
		echo '<a href="http://en.wikipedia.org/wiki/MIME_type#List_of_common_media_types" target="_blank">list of common mime/types</a></p>';
		
        // order option
		echo '<p style="text-align:left"><label for="listattach-ordeby">Order By: <input style="width: 200px;" id="listattach-orderby" name="listattach-orderby" type="text" value="'.$options['orderby'].'" /></label><br/>';
        echo '<a href="http://codex.wordpress.org/Function_Reference/query_posts#Orderby_Parameters" target="_blank">list of orderby values</a></p>';
		
        // order direction option
		echo '<p style="text-align:left"><label for="listattach-order">Order Direction: <input style="width: 200px;" id="listattach-order" name="listattach-order" type="text" value="'.$options['order'].'" /></label><br/>';
		echo 'Valid values: ASC or DESC</p>';
                                
        // display option
		echo '<p style="text-align:left">Display: ';
		echo '<select name="listattach-display" id="listattach-display">';
		$option_arr = array('title', 'caption', 'description');
		foreach($option_arr AS $opt){
			$selected = '';
			if($options['display'] == $opt){
				$selected = 'SELECTED';
			}
			echo '<option value="'.$opt.'" '.$selected.'>'.$opt.'</option>';
		}
		echo '</select></p>';
		
		// scope
		/*
		$checked = '';
		if($options['scope'] == 'post'){
			$checked = 'CHECKED';
		}
		echo '<p style="text-align:left"><input type="checkbox" id="listattach-scope" name="listattach-scope" value="'.$options['scope'].'" '.$checked.'/> <label for="listattach-scope">Limit Scope To Current Post</label></p>';
		*/
		
		// Submit
		echo '<input type="hidden" id="listattach-submit" name="listattach-submit" value="1" />';
	}
	
	// This registers our widget so it appears with the other available
	// widgets and can be dragged and dropped into any active sidebars.
	register_sidebar_widget('Related Attachments', 'widget_listattach');

	// This registers our optional widget control form.
	register_widget_control('Related Attachments', 'widget_listattach_control');
}

// Run code later in case this loads prior to any required plugins.
add_action('plugins_loaded', 'widget_listattach_init');

//the short code
function listattach($atts) {
	global $wpdb, $post;
                
    extract(shortcode_atts(array(
		'type' => 'application',
		'count' => -1,
		'orderby' => 'date',
		'order' => 'DESC',
		'show' => 'post_title',
		'scope' => 'post'
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
		foreach ($attachments as $attachment) {
			//let's add swill's sexy mime type class, shall we?
			$mime_parts = explode("/", $attachment->post_mime_type);
            $mime_class = 'class="mime-'.$mime_parts[count($mime_parts)-1].'"';
			$descr = 'post_title';
			if($show == 'caption'){
				$descr = 'post_excerpt';
			}
			else if($show == 'description'){
				$descr = 'post_content';  
			}
			if($attachment->$descr){
				$lra .= '<li '.$mime_class.'><a href="'.wp_get_attachment_url($attachment->ID).'">'.$attachment->$descr.'</a></li>';     
			}
			else{
				$lra .= '<li '.$mime_class.'>'.wp_get_attachment_link($attachment->ID).'</li>';
			}
		}
		wp_reset_postdata();
		$lra .= '</ul>';
	}
	return $lra;
}

add_shortcode('list-related-attach', 'listattach');

?>
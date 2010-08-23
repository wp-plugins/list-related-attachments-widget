<?php
/*
	Plugin Name: List Related Attachments Widget
	Plugin URI: http://twinpictures.de
	Description: Display a list of related attachments linked to the current post or page
	Version: 1.2
	Author: Twinpictures
	Author URI: http://www.twinpictures.de/related-attachments/
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
			'title' => "Attachments",
			'count' => -1,
			'type' => "application"
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
			'post_parent' => $post->ID
		); 
		$attachments = get_posts($args);
		if ($attachments) {
			echo $before_widget . $before_title .$options['title'] . $after_title;
			echo '<ul>';
			foreach ($attachments as $attachment) {
				//echo '<li>'.$attachment->post_title.'</li>';
				//the_attachment_link($attachment->ID, false);
				echo '<li>'.wp_get_attachment_link($attachment->ID).'</li>';
				
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
function listattach($type = "application", $count = -1) {
	global $wpdb, $post;
	$args = array(
		'post_type' => 'attachment',
		'post_mime_type' => $type,
		'numberposts' => $count,
		'post_parent' => $post->ID
	); 
	
	$attachments = get_posts($args);
	if ($attachments) {
		$lra = '<ul class = "list-related-attach">';
		foreach ($attachments as $attachment) {
			$lra .= '<li>'.wp_get_attachment_link($attachment->ID).'</li>';
		}
		$lra .= '</ul>';
	}
	return $lra;
}

add_shortcode('list-related-attach', 'listattach');

?>
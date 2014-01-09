<?php
/*
Plugin Name: Post Type Manager
Description: Manager to create custom post type & add field to it
Version: 1.0.0
*/

if(is_admin()) {  
	wp_enqueue_script('post-type-manager-js', plugins_url('/js/script.js', __FILE__));  
}

require_once(__DIR__.'/classes/post-type-meta-box.php');
require_once(__DIR__.'/classes/fields/custom-field-text.php');
require_once(__DIR__.'/classes/fields/custom-field-textarea.php');
require_once(__DIR__.'/classes/fields/custom-field-checkbox.php');
require_once(__DIR__.'/classes/fields/custom-field-select.php');
require_once(__DIR__.'/classes/fields/custom-field-radio.php');
require_once(__DIR__.'/classes/fields/custom-field-post-list.php');
require_once(__DIR__.'/classes/fields/custom-field-image.php');
require_once(__DIR__.'/classes/post-type.php');


<?php
/*
Plugin Name: Post Type Manager
Description: Manager to create custom post type & add field to it
Version: 1.0.0
*/

function plugin_register_js() {
	wp_register_script( 'post-type-manager-js', plugins_url('/js/script.js', __FILE__));
	wp_enqueue_script('post-type-manager-js');
}
add_action( 'admin_init','plugin_register_js');

require_once(dirname(__FILE__).'/classes/post-type-meta-box.php');
require_once(dirname(__FILE__).'/classes/fields/custom-field-abstract.php');
require_once(dirname(__FILE__).'/classes/fields/custom-field-text.php');
require_once(dirname(__FILE__).'/classes/fields/custom-field-textarea.php');
require_once(dirname(__FILE__).'/classes/fields/custom-field-checkbox.php');
require_once(dirname(__FILE__).'/classes/fields/custom-field-select.php');
require_once(dirname(__FILE__).'/classes/fields/custom-field-radio.php');
require_once(dirname(__FILE__).'/classes/fields/custom-field-post-list.php');
require_once(dirname(__FILE__).'/classes/fields/custom-field-image.php');
require_once(dirname(__FILE__).'/classes/fields/custom-field-editor.php');
require_once(dirname(__FILE__).'/classes/fields/custom-field-address.php');
require_once(dirname(__FILE__).'/classes/post-type.php');


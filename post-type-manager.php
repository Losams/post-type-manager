<?php
/*
Plugin Name: Post Type Manager
Description: Manager to create custom post type & add field to it
Version: 1.0.0
*/

function ptm_register_js($hook) {
        if(
	        'post-new.php' != $hook &&
	        'post.php' != $hook

        )
            return;
	wp_register_script( 'post-type-manager-js', plugins_url('/js/script.js', __FILE__), array('jquery'), null, true);
	wp_enqueue_script('post-type-manager-js');
}

add_action( 'admin_enqueue_scripts', 'ptm_register_js' );

require_once(plugin_dir_path( __FILE__ ).'classes/post-type-meta-box.php');
require_once(plugin_dir_path( __FILE__ ).'classes/fields/custom-field-abstract.php');
require_once(plugin_dir_path( __FILE__ ).'classes/fields/custom-field-text.php');
require_once(plugin_dir_path( __FILE__ ).'classes/fields/custom-field-textarea.php');
require_once(plugin_dir_path( __FILE__ ).'classes/fields/custom-field-checkbox.php');
require_once(plugin_dir_path( __FILE__ ).'classes/fields/custom-field-select.php');
require_once(plugin_dir_path( __FILE__ ).'classes/fields/custom-field-radio.php');
require_once(plugin_dir_path( __FILE__ ).'classes/fields/custom-field-post-list.php');
require_once(plugin_dir_path( __FILE__ ).'classes/fields/custom-field-image.php');
require_once(plugin_dir_path( __FILE__ ).'classes/fields/custom-field-editor.php');
require_once(plugin_dir_path( __FILE__ ).'classes/fields/custom-field-address.php');
require_once(plugin_dir_path( __FILE__ ).'classes/post-type.php');


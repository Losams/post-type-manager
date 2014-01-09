<?php
/*
Plugin Name: Post Type Manager
Description: Manager to create custom post type & add field to it
Version: 1.0.0
*/

require_once(__DIR__.'/post-type-meta-box.php');

class Post_Type_Manager {
	protected $_name;
	protected $_fields;
	protected $_box_title = 'Custom Meta Box';
	protected $_had_meta_box = false;

	public function __construct($typeName = null) {
		$this->_name = $typeName;

		if(is_admin()) {  
			wp_enqueue_script('post-type-manager-js', plugins_url('/script.js', __FILE__));  
		}

		add_action('save_post', array($this, 'save_custom_meta')); 
	}

	public function generate_post_type(){
		register_post_type( 
			$this->_name,
			array(
				'labels' => array(
				'name' => __( $this->_name ),
				'singular_name' => __( $this->_name )
				),
				'public' => true
			)
		);		
	}

	public function create_meta_box($name) {
		return new Post_Type_Meta_Box($name, $this->_name);
	}

	public function set_box_title($box_title){
		$this->_box_title = $box_title;
	}

	public function add_field($label, $type, $desc = null, $options = array(), $post_type = array()) {
		if (!$this->_had_meta_box) {
			add_action('add_meta_boxes', array( $this, 'add_custom_meta_box' ));
			$this->_had_meta_box = true;
		}

		$this->_fields[$label] = array(
			'label' 	=> $label,
			'desc'		=> $desc,
			'id'		=> 'custom_'.$type,
			'type'		=> $type,
			'options' 	=> $options,
			'post_type' => array('post',$post_type)  
		);
	}

	public function add_custom_meta_box() {  
	    add_meta_box(  
	        'custom_meta_box', // $id  
	        $this->_box_title, // $title   
	        array($this, 'show_custom_meta_box'), // $callback  
	        $this->_name, // $page  
	        'normal', // $context  
	        'high'); // $priority  
	}  

	public function show_custom_meta_box() {  
		global $post;  

		echo '<input type="hidden" name="custom_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';  
	    echo '<table class="form-table">';  

	    foreach ($this->_fields as $field) {  
	        // get value of this field if it exists for this post  
	        $meta = get_post_meta($post->ID, $field['id'], true);  
	        
	        echo '<tr> 
	                <th><label for="'.$field['id'].'">'.$field['label'].'</label></th> 
	                <td>';  

	                switch($field['type']) {  
	                    case 'text':  
						    echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" /> 
						        <br /><span class="description">'.$field['desc'].'</span>';  
							break;  
						case 'textarea':  
						    echo '<textarea name="'.$field['id'].'" id="'.$field['id'].'" cols="60" rows="4">'.$meta.'</textarea> 
						        <br /><span class="description">'.$field['desc'].'</span>';  
							break;  
						case 'checkbox':  
						    echo '<input type="checkbox" name="'.$field['id'].'" id="'.$field['id'].'" ',$meta ? ' checked="checked"' : '','/> 
						        <label for="'.$field['id'].'">'.$field['desc'].'</label>';  
							break;
						case 'select':  
						    echo '<select name="'.$field['id'].'" id="'.$field['id'].'">';  
						    foreach ($field['options'] as $option) {  
						        echo '<option', $meta == $option['value'] ? ' selected="selected"' : '', ' value="'.$option['value'].'">'.$option['label'].'</option>';  
						    }  
						    echo '</select><br /><span class="description">'.$field['desc'].'</span>';  
							break;  
						case 'radio':  
						    foreach ( $field['options'] as $option ) {  
						        echo '<input type="radio" name="'.$field['id'].'" id="'.$option['value'].'" value="'.$option['value'].'" ',$meta == $option['value'] ? ' checked="checked"' : '',' /> 
						                <label for="'.$option['value'].'">'.$option['label'].'</label><br />';  
						    }  
							break;  
						case 'post_list':  
							$items = get_posts( array ( 'post_type' => $field['post_type'],  'posts_per_page' => -1 ));  
							echo '<select name="'.$field['id'].'" id="'.$field['id'].'"> 
							        <option value="">Select One</option>';
							        foreach($items as $item) {  
							            echo '<option value="'.$item->ID.'"',$meta == $item->ID ? ' selected="selected"' : '','>'.$item->post_type.': '.$item->post_title.'</option>';  
							        } 
							    echo '</select><br /><span class="description">'.$field['desc'].'</span>';  
							break; 
						case 'image':  
						    if ($meta) { $image = wp_get_attachment_image_src($meta, 'medium'); $image = $image[0]; }                 
						    echo    '<input name="'.$field['id'].'" type="hidden" class="custom_upload_image" value="'.$meta.'" /> 
						                <img src="'.$image.'" class="custom_preview_image" alt="" /><br /> 
						                    <input class="custom_upload_image_button button" type="button" value="Choose Image" /> 
						                    <small> <a href="#" class="custom_clear_image_button">Remove Image</a></small> 
						                    <br clear="all" /><span class="description">'.$field['desc'].'';  
							break;   
	                }  
	        echo '</td></tr>';  
	    }  
	    echo '</table>';
	}  

	public function save_custom_meta($post_id) {  
	    // verify nonce  
	    if (!wp_verify_nonce($_POST['custom_meta_box_nonce'], basename(__FILE__)))   
	        return $post_id;  
	    
	    // check autosave  
	    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)  
	        return $post_id;  

	    // check permissions  
	    if ('page' == $_POST['post_type']) {  
	        if (!current_user_can('edit_page', $post_id))  
	            return $post_id;  
	        } elseif (!current_user_can('edit_post', $post_id)) {  
	            return $post_id;  
	    }  
	      
	    // loop through fields and save the data  
	    foreach ($this->_fields as $field) {  
	        $old = get_post_meta($post_id, $field['id'], true);  
	        $new = $_POST[$field['id']];  
	        if ($new && $new != $old) {  
	            update_post_meta($post_id, $field['id'], $new);  
	        } elseif ('' == $new && $old) {  
	            delete_post_meta($post_id, $field['id'], $old);  
	        }  
	    }
	}  
}
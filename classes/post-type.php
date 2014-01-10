<?php

class Post_Type_Manager {
	protected $_name;
	protected $_fields 			= array();
	protected $_supports 		= array('title', 'editor'); //array('title', 'editor', 'excerpt', 'thumbnail');
	protected $_taxonomies		= array(); //array('post_tag','category');
	protected $_hierarchical	= true;
	protected $_has_archive		= true;

	protected $_box_title 		= 'Custom Meta Box';
	protected $_had_meta_box 	= false;

	public function __construct($typeName = null) {
		$this->_name = $typeName;

		add_action('save_post', array($this, 'save_custom_meta')); 
	}

	/**
	 * Generate the post type with all the previous configuration set
	 */
	public function generate_post_type(){
		register_post_type( 
			$this->_name,
			array(
				'labels' => array(
					'name' 			=> __( $this->_name ),
					'singular_name' => __( $this->_name )
				),
				'public' 		=> true,
				'supports' 		=> $this->_supports,
				'hierarchical' 	=> $this->_hierarchical,
        		'has_archive' 	=> $this->_has_archive,
        		'taxonomies'	=> $this->_taxonomies,
        		'can_export' 	=> true
			)
		);	
	}

	public function create_meta_box($name) {
		return new Post_Type_Meta_Box($name, $this->_name);
	}

	/**
	 * Function to add support before post type generation
	 */
	public function add_support($supp) {
		if(($key = array_search($supp, $this->_supports)) === false) {
			$this->_supports[] = $supp;
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Function to remove support before post type generation
	 */
	public function remove_support($supp) {
		if(($key = array_search($supp, $this->_supports)) !== false) {
		    unset($this->_supports[$key]);
		    return true;
		} else {
			return false;
		}
	}

	/**
	 * Function to add taxonomie before post type generation
	 */
	public function add_taxonomy($tax) {
		if(($key = array_search($tax, $this->_taxonomies)) === false) {
			$this->_taxonomies[] = $tax;
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Function to remove taxonomie before post type generation
	 */
	public function remove_taxonomy($tax) {
		if(($key = array_search($tax, $this->_taxonomies)) !== false) {
		    unset($this->_taxonomies[$key]);
		    return true;
		} else {
			return false;
		}
	}

	/**
	 * Change de Meta box label
	 */
	public function set_box_title($box_title){
		$this->_box_title = $box_title;
	}

	/**
	 * Check if a metabox is already create, if it's not the case, create it
	 */
	public function check_metabox() {
		if (!$this->_had_meta_box) {
			add_action('add_meta_boxes', array( $this, 'add_custom_meta_box' ));
			$this->_had_meta_box = true;
		}
	}

	/**
	 * add custom text field to the instance's post type
	 * @param [string] $label 
	 * @param [string] $desc  
	 */
	public function add_text_field($label, $desc = null) {
		$this->check_metabox();
		$this->_fields[] = new Custom_Field_Text($label, $desc);
	}

	/**
	 * add custom textarea field to the instance's post type
	 * @param [string] $label 
	 * @param [string] $desc  
	 */
	public function add_textarea_field($label, $desc = null) {
		$this->check_metabox();
		$this->_fields[] = new Custom_Field_Textarea($label, $desc);
	}

	/**
	 * add custom checkbox field to the instance's post type
	 * @param [string] $label 
	 * @param [string] $desc  
	 */
	public function add_checkbox_field($label, $desc = null) {
		$this->check_metabox();
		$this->_fields[] = new Custom_Field_Checkbox($label, $desc);
	}

	/**
	 * add custom select field to the instance's post type
	 * @param [string] $label 
	 * @param [string] $desc  
	 * @param [array] $options 
	 */
	public function add_select_field($label, $desc = null, $options = array()) {
		$this->check_metabox();
		$this->_fields[] = new Custom_Field_Select($label, $desc, $options);
	}

	/**
	 * add custom radio field to the instance's post type
	 * @param [string] $label 
	 * @param [string] $desc  
	 * @param [array] $options 
	 */
	public function add_radio_field($label, $desc = null, $options = array()) {
		$this->check_metabox();
		$this->_fields[] = new Custom_Field_Radio($label, $desc, $options);
	}

	/**
	 * add custom post list field to the instance's post type
	 * @param [string] $label 
	 * @param [string] $desc  
	 * @param [string] $post_type 
	 */
	public function add_post_list_field($label, $desc = null, $post_type = null) {
		$this->check_metabox();
		$this->_fields[] = new Custom_Field_Post_List($label, $desc, $post_type);
	}

	/**
	 * add custom image field to the instance's post type
	 * @param [string] $label 
	 * @param [string] $desc  
	 */
	public function add_image_field($label, $desc = null) {
		$this->check_metabox();
		$this->_fields[] = new Custom_Field_Image($label, $desc);
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
	        $meta = get_post_meta($post->ID, $field->get_id(), true);  
	        
	        echo '<tr> 
	                <th><label for="'.$field->get_id().'">'.$field->get_label().'</label></th> 
	                <td>';  
	                	echo $field->output($meta);
	        echo '</td></tr>';  
	    }  
	    echo '</table>';
	}  

	/**
	 * function processing the field's save of the instance's post type
	 */
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
	        
	        $old = get_post_meta($post_id, $field->get_id(), true);  
	        $new = $_POST[$field->get_id()];

	        if ($new && $new != $old) {  
	        	// pre save allow special treatment for each field before saving
	        	$new = $field->pre_save($new);
	            update_post_meta($post_id, $field->get_id(), $new);  
	        } elseif ('' == $new && $old) {  
	            delete_post_meta($post_id, $field->get_id(), $old);  
	        }  
	    }
	}  
}
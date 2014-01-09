<?php

class Post_Type_Manager {
	protected $_name;
	protected $_fields = array();

	protected $_box_title = 'Custom Meta Box';
	protected $_had_meta_box = false;

	public function __construct($typeName = null) {
		$this->_name = $typeName;

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

	public function check_metabox() {
		if (!$this->_had_meta_box) {
			add_action('add_meta_boxes', array( $this, 'add_custom_meta_box' ));
			$this->_had_meta_box = true;
		}
	}

	public function add_text_field($label, $desc = null) {
		$this->check_metabox();
		$this->_fields[] = new Custom_Field_Text($label, $desc);
	}

	public function add_textarea_field($label, $desc = null) {
		$this->check_metabox();
		$this->_fields[] = new Custom_Field_Textarea($label, $desc);
	}

	public function add_checkbox_field($label, $desc = null) {
		$this->check_metabox();
		$this->_fields[] = new Custom_Field_Checkbox($label, $desc);
	}

	public function add_select_field($label, $desc = null, $options = array()) {
		$this->check_metabox();
		$this->_fields[] = new Custom_Field_Select($label, $desc, $options);
	}

	public function add_radio_field($label, $desc = null, $options = array()) {
		$this->check_metabox();
		$this->_fields[] = new Custom_Field_Radio($label, $desc, $options);
	}

	public function add_post_list_field($label, $desc = null, $post_type = null) {
		$this->check_metabox();
		$this->_fields[] = new Custom_Field_Post_List($label, $desc, $post_type);
	}

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
	            update_post_meta($post_id, $field->get_id(), $new);  
	        } elseif ('' == $new && $old) {  
	            delete_post_meta($post_id, $field->get_id(), $old);  
	        }  
	    }
	}  
}
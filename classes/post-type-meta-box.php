<?php 

class Post_Type_Meta_Box {
	protected $_id;
	protected $_title;
	protected $_type_post;

	public $_fields = array();

	public function __construct($id = null, $title = null, $type_post = null) {
		if ($id && $title) {
			$this->_id = $id;
			$this->_title = $title;
			$this->_type_post = $type_post;

			add_action('add_meta_boxes', array( $this, 'add_custom_meta_box' ));
		}		
	}

	public function add_custom_meta_box() {  
	    add_meta_box(  
	        $this->_id, // $id  
	        $this->_title, // $title   
	        array( $this, 'show_custom_meta_box'), // $callback  
	        $this->_type_post, // $page  
	        'normal', // $context  
	        'high'); // $priority  
	}  


	public function show_custom_meta_box( $post ) {  

		echo '<input type="hidden" name="custom_meta_box_nonce" value="'.wp_create_nonce('post-type.php').'" />';  
	    echo '<table class="form-table">';  

	    foreach ($this->_fields as $field) {  
	        
	        echo '<tr> 
	                <th><label for="'.$field->get_id().'">'.$field->get_label().'</label></th> 
	                <td>';
	                if ($field->get_params('repeatable')) {
	                	$meta = get_post_meta($post->ID, $field->get_id());  
	                	echo $field->output_repeatable($meta);	
	                } else {
				        $meta = get_post_meta($post->ID, $field->get_id(), true);  
	                	echo $field->output($meta);	
	                }
	                
	        echo '</td></tr>';  
	    }  
	    echo '</table>';
	}  

}
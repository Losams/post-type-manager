<?php 

class Post_Type_Meta_Box {
	protected $_name;
	protected $_type_post;

	public function __construct($name = null, $type_post = null) {
		if ($name && $type_post) {
			$this->_name = $name;
			$this->_type_post = $type_post;
		}

		add_action('add_meta_boxes', array( $this, 'add_custom_meta_box' ));
	}

	public function add_custom_meta_box() {  
	    add_meta_box(  
	        'custom_meta_box_'.$this->_name, // $id  
	        $this->_name, // $title   
	        array($this, 'show_custom_meta_box'), // $callback  
	        $this->_type_post, // $page  
	        'normal', // $context  
	        'high'); // $priority  
	}  

}
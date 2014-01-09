<?php 

class Custom_Field_Post_List {
	protected $_label;
	protected $_description;
	protected $_post_type;
	protected $_id;

	public function __construct($label, $desc = null, $post_type = null) {
		$this->_label = $label;
		$this->_description = $desc;
		$this->_post_type = $post_type;

		$label = str_replace(array('\'', ' ', '"'), '_', $label);
		$this->_id = 'custom_post_list_'.$label;
	}

	public function output($meta){

		$items = get_posts( array ( 'post_type' => $this->_post_type,  'posts_per_page' => -1 )); 

		$html = '<select name="'.$this->_id.'" id="'.$this->_id.'"> 
		        <option value="">Select One</option>';
		        foreach($items as $item) {  
		            $html .= '<option value="'.$item->ID.'"'. ($meta == $item->ID ? ' selected="selected"' : '') .'>'.$item->post_type.': '.$item->post_title.'</option>';  
		        } 
	    $html .= '</select><br /><span class="description">'.$this->_description.'</span>';  
	    
		return $html; 
	}

	public function get_id() {
		return $this->_id;
	}

	public function get_label() {
		return $this->_label;
	}
}
<?php 

class Custom_Field_Textarea {
	protected $_label;
	protected $_description;
	protected $_id;

	public function __construct($label, $desc = null) {
		$this->_label = $label;
		$this->_description = $desc;
		
		$label = str_replace(array('\'', ' ', '"'), '_', $label);
		$this->_id = 'custom_textarea_'.$label;
	}

	public function output($meta){
		return '<textarea name="'.$this->_id.'" id="'.$this->_id.'" cols="60" rows="4">'.$meta.'</textarea> 
		       <br /><span class="description">'.$this->_description.'</span>'; 
	}

	public function get_id() {
		return $this->_id;
	}

	public function get_label() {
		return $this->_label;
	}
}
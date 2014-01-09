<?php 

class Custom_Field_Textarea extends Custom_Field_Abstract{

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
}
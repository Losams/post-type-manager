<?php 

class Custom_Field_Textarea extends Custom_Field_Abstract{

	protected $_prefix_id = 'custom_textarea_';

	public function field_html($meta){
		return '<textarea name="'.$this->_name.'" id="'.$this->_id.'" cols="60" rows="4">'.$meta.'</textarea>'; 
	}
}
<?php 

class Custom_Field_Text extends Custom_Field_Abstract{

	protected $_prefix_id = 'custom_text_';

	public function field_html($meta){
		return 	'<input type="text" name="'.$this->_name.'" id="'.$this->_id.'" value="'.$meta.'" size="30" />'; 
	}
}
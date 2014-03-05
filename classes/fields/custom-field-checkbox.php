<?php 

class Custom_Field_Checkbox extends Custom_Field_Abstract{
	
	protected $_prefix_id = 'custom_checkbox_';

	public function field_html($meta){
		return '<input type="checkbox" value="1" name="'.$this->_name.'" id="'.$this->_id.'" '. ($meta ? ' checked="checked"' : '') .'/>'; 
	}
}
<?php 

class Custom_Field_Select extends Custom_Field_Abstract{

	protected $_prefix_id = 'custom_select_';

	public function field_html($meta){
		$options = $this->_params['options'];

		$html = '<select name="'.$this->_name.'" id="'.$this->_id.'">';  
	    foreach ($options as $option) {  
	        $html .= '<option'. ($meta == $option['value'] ? ' selected="selected"' : '') .' value="'.$option['value'].'">'.$option['label'].'</option>';  
	    }  
		$html .='</select>';  

		return $html; 
	}
}
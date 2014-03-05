<?php 

class Custom_Field_Radio extends Custom_Field_Abstract{
	
	protected $_prefix_id = 'custom_radio_';
	
	public function field_html($meta){
		$options = $this->_params['options'];

		$html = '';
		foreach ( $options as $option ) {  
	        $html .= 	'<input type="radio" name="'.$this->_name.'" id="'.$option['value'].'" value="'.$option['value'].'" '. ($meta == $option['value'] ? ' checked="checked"' : '') .' /> 
	                	<label for="'.$option['value'].'">'.$option['label'].'</label>';  
	    }

		return $html; 
	}
}
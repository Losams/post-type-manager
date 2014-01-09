<?php 

class Custom_Field_Radio extends Custom_Field_Abstract{
	
	protected $_options;

	public function __construct($label, $desc = null, $options = array()) {
		$this->_label = $label;
		$this->_description = $desc;
		$this->_options = $options;

		$label = str_replace(array('\'', ' ', '"'), '_', $label);
		$this->_id = 'custom_radio_'.$label;
	}

	public function output($meta){
		$html = '';
		foreach ( $this->_options as $option ) {  
	        $html .= 	'<input type="radio" name="'.$this->_id.'" id="'.$option['value'].'" value="'.$option['value'].'" '. ($meta == $option['value'] ? ' checked="checked"' : '') .' /> 
	                	<label for="'.$option['value'].'">'.$option['label'].'</label><br />';  
	    }

		return $html; 
	}
}
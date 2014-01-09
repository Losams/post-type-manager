<?php 

class Custom_Field_Radio {
	protected $_label;
	protected $_description;
	protected $_options;
	protected $_id;

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

	public function get_id() {
		return $this->_id;
	}

	public function get_label() {
		return $this->_label;
	}
}
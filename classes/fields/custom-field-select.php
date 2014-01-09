<?php 

class Custom_Field_Select extends Custom_Field_Abstract{

	protected $_options;

	public function __construct($label, $desc = null, $options = array()) {
		$this->_label = $label;
		$this->_description = $desc;
		$this->_options = $options;

		$label = str_replace(array('\'', ' ', '"'), '_', $label);
		$this->_id = 'custom_select_'.$label;
	}

	public function output($meta){
		$html = '<select name="'.$this->_id.'" id="'.$this->_id.'">';  
	    foreach ($this->_options as $option) {  
	        $html .= '<option'. ($meta == $option['value'] ? ' selected="selected"' : '') .' value="'.$option['value'].'">'.$option['label'].'</option>';  
	    }  
		$html .='</select><br /><span class="description">'.$this->_description.'</span>';  

		return $html; 
	}
}
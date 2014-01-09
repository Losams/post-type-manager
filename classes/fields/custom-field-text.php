<?php 

class Custom_Field_Text extends Custom_Field_Abstract{

	public function __construct($label, $desc = null) {
		$this->_label = $label;
		$this->_description = $desc;

		$label = str_replace(array('\'', ' ', '"'), '_', $label);
		$this->_id = 'custom_text_'.$label;
	}

	public function output($meta){
		return 	'<input type="text" name="'.$this->_id.'" id="'.$this->_id.'" value="'.$meta.'" size="30" /> 
				<br /><span class="description">'.$this->_description.'</span>'; 
	}


}
<?php 

class Custom_Field_Checkbox {
	protected $_label;
	protected $_description;
	protected $_id;

	public function __construct($label, $desc = null) {
		$this->_label = $label;
		$this->_description = $desc;

		$label = str_replace(array('\'', ' ', '"'), '_', $label);
		$this->_id = 'custom_checkbox_'.$label;
	}

	public function output($meta){
		return '<input type="checkbox" name="'.$this->_id.'" id="'.$this->_id.'" '. ($meta ? ' checked="checked"' : '') .'/> 
				<label for="'.$this->_id.'">'.$this->_description.'</label>'; 
	}

	public function get_id() {
		return $this->_id;
	}

	public function get_label() {
		return $this->_label;
	}
}
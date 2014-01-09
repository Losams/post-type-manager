<?php 

abstract class Custom_Field_Abstract {
	protected $_label;
	protected $_description;
	protected $_id;

	public function pre_save($value) {
		return $value;		
	}

	public function get_id() {
		return $this->_id;
	}

	public function get_label() {
		return $this->_label;
	}
}
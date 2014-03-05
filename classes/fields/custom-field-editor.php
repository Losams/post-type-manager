<?php 

class Custom_Field_Editor extends Custom_Field_Abstract{

	protected $_prefix_id = 'custom_editor_';

	public function field_html($meta){
		wp_editor($meta, $this->_id, array(
                        'wpautop'       => true,
                        'media_buttons' => false,
                        'textarea_name' => $this->_name,
                        'textarea_rows' => 10,
                        'teeny'         => false
                ));
		return;
	}
}
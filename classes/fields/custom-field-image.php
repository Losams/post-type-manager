<?php 

class Custom_Field_Image extends Custom_Field_Abstract{
	
	public function __construct($label, $desc = null, $post_type = null) {
		$this->_label = $label;
		$this->_description = $desc;

		$label = str_replace(array('\'', ' ', '"'), '_', $label);
		$this->_id = 'custom_image_'.$label;
	}

	public function output($meta){

		if ($meta) { $image = wp_get_attachment_image_src($meta, 'medium'); $image = $image[0]; }                 
	    $html =    '<input name="'.$this->_id.'" type="hidden" class="custom_upload_image" value="'.$meta.'" /> 
	                <img src="'.$image.'" class="custom_preview_image" alt="" /><br /> 
	                    <input class="custom_upload_image_button button" type="button" value="Choose Image" /> 
	                    <small> <a href="#" class="custom_clear_image_button">Remove Image</a></small> 
	                    <br clear="all" /><span class="description">'.$this->_description.'';  
	    
		return $html; 
	}
}
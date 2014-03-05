<?php 

class Custom_Field_Image extends Custom_Field_Abstract{
	
	protected $_prefix_id = 'custom_image_';

	public function field_html($meta){

		$image = null;
		if ($meta) { $image = wp_get_attachment_image_src($meta, 'medium'); $image = $image[0]; }                 
	    $html =    '<input name="'.$this->_name.'" type="hidden" class="custom_upload_image" value="'.$meta.'" /> 
	                <img src="'.$image.'" class="custom_preview_image" alt="" /><br /> 
	                    <input class="custom_upload_image_button button" type="button" value="Choose Image" /> 
	                    <small> <a href="#" class="custom_clear_image_button">Remove Image</a></small>';  
	    
		return $html; 
	}
}
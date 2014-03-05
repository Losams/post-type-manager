<?php 

class Custom_Field_Address extends Custom_Field_Abstract{
	
	protected $_prefix_id = 'custom_address_';

	public function field_html($meta){
		return 	'<input type="text" name="'.$this->_name.'" id="'.$this->_id.'" value="'.$meta.'" size="30" />'; 
	}

	public function pre_save($post_id, $new, $old) {
		// get lat - long
	    $address 	= urlencode(str_replace(',', '', $new));
	    $url 		= "http://maps.googleapis.com/maps/api/geocode/json?address=$address&sensor=false";
	    $getmap 	= file_get_contents($url);
	    $googlemap 	= json_decode($getmap);
	    
		$res = reset($googlemap->results);

		if ($res) {
		    $address = $res->geometry;
		    $latlng = $address->location;
		    
		    if ($latlng) {
		    	update_post_meta($post_id, $this->get_id().'_lat', $latlng->lat);
		    	update_post_meta($post_id, $this->get_id().'_lng', $latlng->lng);
			}
		}

		return $new;		
	}

	public function pre_delete($post_id, $old) {
		delete_post_meta($post_id, $this->get_id().'_lat');  
		delete_post_meta($post_id, $this->get_id().'_lng');  
		
		return true;
	}
}
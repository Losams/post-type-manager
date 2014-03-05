<?php 

class Custom_Field_Post_List extends Custom_Field_Abstract{

	protected $_prefix_id = 'custom_post_list_';

	public function field_html($meta){

		$post_type = $this->_params['post_type'];

		// If array param, we use it to get post
		if (!is_array($post_type)) {
			$items = get_posts( array( 'post_type' => $post_type,  'posts_per_page' => -1 ) ); 
		} else {
			$items = get_posts( $post_type ); 
		}
		
		$html = '<select name="'.$this->_name.'" id="'.$this->_id.'"> 
		        <option value="">Select One</option>';
		        foreach($items as $item) {  
		            $html .= '<option value="'.$item->ID.'"'. ($meta == $item->ID ? ' selected="selected"' : '') .'>'.$item->post_type.': '.$item->post_title.'</option>';  
		        } 
	    $html .= '</select>';  
	    
		return $html; 
	}
}
<?php 

abstract class Custom_Field_Abstract {
	protected $_label;
	protected $_description;
	protected $_params;
	protected $_id;

	public function __construct($label, $desc = null, $params = array()) {
		$this->_label = $label;
		$this->_description = $desc;
		$this->_params = $params;

		// Sanitize ID field
		$label = strtolower(str_replace(array('\'', ' ', '"'), '_', $this->_label));
		$this->_id = $this->_prefix_id.$label;

		// Change name of input in case of repeatable field
		if ($this->_params['repeatable']) {
			$this->_name = $this->_id.'[]';
		} else {
			$this->_name = $this->_id;
		}
	}

	public function output_repeatable($meta) {

		$html = '<a class="repeatable-add button" href="#">+</a>
            	<ul id="'.$this->_id.'-repeatable" class="custom_repeatable">';

	    if ($meta) {
	        foreach($meta as $row) {
	        	$html .= '<li><span class="sort hndle">|||</span>';
	            $html .= $this->field_html($row);
	            $html .= '<a class="repeatable-remove button" href="#">-</a></li>';
	        }
	    } else {
	    	$html .= '<li><span class="sort hndle">|||</span>';
	    	$html .= $this->field_html(null);
	    	$html .= '<a class="repeatable-remove button" href="#">-</a></li>';
	    }

	    $html .= '</ul>';
	    $html .= $this->get_description();

	    return $html;
	}

	public function output($meta) {
		$html = $this->field_html($meta);
		$html .= $this->get_description();
		return $html;
	}

	public function get_description() {
		return '<br /><span class="description">'.$this->_description.'</span>';
	}

	public function pre_save($post_id, $new, $old) {
		return $new;		
	}

	public function pre_delete($post_id, $old) {
		return true;
	}

	public function get_id() {
		return $this->_id;
	}

	public function get_label() {
		return $this->_label;
	}

	public function get_params( $param = null ) {
		if (!$param) {
			return $this->_params;
		} else {
			if (isset($this->_params[$param])) {
				return $this->_params[$param];
			}
		}
		return false;
	}

	public function save($post_id) {
		$old = get_post_meta($post_id, $this->get_id(), true);  
        $new = $_POST[$this->get_id()];

        // Spécial save for repeatable Fields
        if ($this->_params['repeatable']) {
			delete_post_meta($post_id, $this->get_id());
			
			// pre save allow special treatment for each field before saving
	        $new = $this->pre_save($post_id, $new, $old);
			foreach ($new as $n) {
	            add_post_meta($post_id, $this->get_id(), $n); 		
			}	
		} else { // Classic fields
			if ($new && $new != $old) {  
	        	// pre save allow special treatment for each field before saving
	        	$new = $this->pre_save($post_id, $new, $old);
	            update_post_meta($post_id, $this->get_id(), $new);  
	        } elseif ('' == $new && $old) {
	        	$this->pre_delete($post_id, $old);
	            delete_post_meta($post_id, $this->get_id(), $old);  
	        }  	
		}
	}
}
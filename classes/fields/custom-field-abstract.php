<?php 

abstract class Custom_Field_Abstract {
	/**
	 * @var string The field slug, used as name and ID in HTML markup
	 */
	protected $_slug;

	/**
	 * @var string the readable label, used for user experience
	 */
	protected $_label;

	/**
	 * @var string the description to help user to fill field
	 */
	protected $_description;

	/**
	 * @var array Complementary params
	 */
	protected $_params;

	/**
	 * @var string ID used in repeatable case
	 */
	protected $_id;

	/**
	 * @param $slug the slug wanted
	 * @param null $desc Field description
	 * @param array $params Complementary params
	 */
	public function __construct($slug, $desc = null, $params = array()) {
		$this->_slug = $slug;

		// If label provided, we use it. If not, use the slug.
		if(isset($params['label']) && !empty($params['label']))
			$this->_label = $params['label'];
		else $this->_label = $slug;

		$this->_description = $desc;
		$this->_params = $params;

		// Sanitize ID field
		$slug = strtolower(str_replace(array('\'', ' ', '"'), '_', $this->_slug));
		$this->_id = $this->_prefix_id.$slug;

		// Change name of input in case of repeatable field
		if (isset($this->_params['repeatable'])) {
			$this->_name = $this->_id.'[]';
		} else {
			$this->_name = $this->_id;
		}
	}

	/**
	 * @param $meta
	 *
	 * @return string
	 */
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

	/**
	 * @param $meta
	 *
	 * @return string
	 */
	public function output($meta) {
		$html = $this->field_html($meta);
		$html .= $this->get_description();
		return $html;
	}

	/**
	 * Field description getter
	 * @return string
	 */
	public function get_description() {
		return '<br /><span class="description">'.$this->_description.'</span>';
	}

	/**
	 * Pre-save post delegate
	 * @param $post_id the post ID to be saved
	 * @param $new the field new value
	 * @param $old the field old value
	 *
	 * @return mixed the new value
	 */
	public function pre_save($post_id, $new, $old) {
		return $new;		
	}

	/**
	 * Pre-delete post delegate
	 * @param $post_id the post ID to be deleted
	 * @param $old the field old value
	 *
	 * @return bool true if OK, false otherwise
	 */
	public function pre_delete($post_id, $old) {
		return true;
	}

	/**
	 * @return string Field ID getter
	 */
	public function get_id() {
		return $this->_id;
	}

	/**
	 * @return string Field slug getter
	 */
	public function get_slug() {
		return $this->_slug;
	}

	/**
	 * @return string Field label getter
	 */
	public function get_label() {
		return $this->_label;
	}

	/**
	 * Complementary params getter
	 * @param string $param any wanted param*
	 * @return array|bool Param if supplied and found, false otherwise
	 */
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

	/**
	 * Save logic for field
	 * @param $post_id the ost Id to be saved
	 */
	public function save($post_id) {
		$old = get_post_meta($post_id, $this->get_id(), true);  
        $new = $_POST[$this->get_id()];

        // Special save for repeatable Fields
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
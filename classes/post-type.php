<?php

class Post_Type_Manager {
	protected $_name;
	protected $_arg				= array();
	protected $_supports 		= array('title', 'editor'); //array('title', 'editor', 'excerpt', 'thumbnail');
	protected $_taxonomies		= array(); //array('post_tag','category');
	protected $_hierarchical	= true;
	protected $_has_archive		= true;

	protected $_meta_box 		= array();
	protected $_box_title 		= 'Custom Meta Box';

	const ID_DEFAULT_METABOX = 'custom_metabox_default';

	public function __construct($typeName = null, $arg = array()) {
		$this->_name 	= $typeName;
		$this->_arg		= $arg;

		add_action('save_post', array($this, 'save_custom_meta'));
	}

	/**
	 * Generate the post type with all the previous configuration set
	 */
	public function generate_post_type(){
		$register_arg = array(
			'labels' => array(
				'name' 			=> __( $this->_name, 'custom-post-type-manager' ),
				'singular_name' => __( $this->_name, 'custom-post-type-manager' )
			),
			'rewrite' => array(
				'slug' => __( $this->_name, 'custom-post-type-manager' )
			),
			'public' 		=> true,
			'supports' 		=> $this->_supports,
			'hierarchical' 	=> $this->_hierarchical,
			'has_archive' 	=> $this->_has_archive,
			'taxonomies'	=> $this->_taxonomies,
			'can_export' 	=> true
		);

		$register_arg = array_merge($register_arg, $this->_arg);

		register_post_type( $this->_name, $register_arg	);
	}

	/**
	 * Function to add support before post type generation
	 */
	public function add_support($supp) {
		if(($key = array_search($supp, $this->_supports)) === false) {
			$this->_supports[] = $supp;
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Function to remove support before post type generation
	 */
	public function remove_support($supp) {
		if(($key = array_search($supp, $this->_supports)) !== false) {
			unset($this->_supports[$key]);
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Function to add taxonomie before post type generation
	 */
	public function add_taxonomy($tax) {
		if(($key = array_search($tax, $this->_taxonomies)) === false) {
			$this->_taxonomies[] = $tax;
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Function to remove taxonomie before post type generation
	 */
	public function remove_taxonomy($tax) {
		if(($key = array_search($tax, $this->_taxonomies)) !== false) {
			unset($this->_taxonomies[$key]);
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Change de Meta box label
	 */
	public function set_box_title($box_title){
		$this->_box_title = $box_title;
	}


	/**
	 * Get all id in this instance for debugging
	 */
	public function get_all_ids() {
		$ids = array();
		foreach ($this->_meta_box as $metabox) {
			foreach ($metabox->_fields as $field) {
				$ids[$field->get_id()] = $field->get_slug();
			}
		}
		return $ids;
	}

	/**
	 * add custom field to the instance's post type
	 * @param [string] $type
	 * @param [string] $label
	 * @param [string] $desc
	 * @param [array] $params
	 */
	public function add_field($type, $slug, $desc = null, $params = null, $id_metabox = null) {
		if ($id_metabox) {
			if (!isset($this->_meta_box[$id_metabox])) {
				throw new Exception("No metabox named : '".$id_metabox."' found", 1);
			}
		} else {
			$this->check_metabox();
			$id_metabox = self::ID_DEFAULT_METABOX;
		}

		$class_name = 'Custom_Field_'.ucfirst(strtolower($type));

		if (class_exists($class_name)) {
			$this->_meta_box[$id_metabox]->_fields[] = new $class_name($slug, $desc, $params);
			// $this->_fields[] = new $class_name($label, $desc, $params);	
			return true;
		}
		return false;
	}

	/**
	 * Check if a metabox is already create, if it's not the case, create it
	 */
	public function check_metabox() {
		if (!isset($this->_meta_box[self::ID_DEFAULT_METABOX])) {
			$this->_meta_box[self::ID_DEFAULT_METABOX] = new Post_Type_Meta_Box( self::ID_DEFAULT_METABOX, $this->_box_title, $this->_name );
		}
	}

	public function create_meta_box($id, $title) {
		$this->_meta_box[$id] = new Post_Type_Meta_Box( $id, $title, $this->_name );
	}

	/**
	 * function processing the field's save of the instance's post type
	 */
	public function save_custom_meta($post_id) {

		if (!isset($_POST['custom_meta_box_nonce'])) {
			return $post_id;
		}

		// verify nonce
		if (!wp_verify_nonce($_POST['custom_meta_box_nonce'], basename(__FILE__)))
			return $post_id;

		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
			return $post_id;

		// check permissions
		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id))
				return $post_id;
		} elseif (!current_user_can('edit_post', $post_id)) {
			return $post_id;
		}

		// loop through fields and save the data
		foreach ($this->_meta_box as $metabox) {
			foreach ($metabox->_fields as $field) {
				if (isset($_POST[$field->get_id()])) {
					$field->save($post_id);
				}
			}
		}

	}
}

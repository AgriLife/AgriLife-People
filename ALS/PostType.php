<?php

class ALS_PostType {

	private static $instance;

	public function __construct() {

		self::$instance = $this;

		// Backend labels
		$labels = array(
			'name' => __( 'Staff', 'agrilife' ),
			'singular_name' => __( 'Staff', 'agrilife' ),
			'add_new' => __( 'Add New', 'agrilife' ),
			'add_new_item' => __( 'Add New Staff', 'agrilife' ),
			'edit_item' => __( 'Edit Staff', 'agrilife' ),
			'new_item' => __( 'New Staff', 'agrilife' ),
			'view_item' => __( 'View Staff', 'agrilife' ),
			'search_items' => __( 'Search Staff', 'agrilife' ),
			'not_found' => __( 'No Staff Found', 'agrilife' ),
			'not_found_in_trash' => __( 'No staff found in trash', 'agrilife' ),
			'parent_item_colon' => '',
			'menu_name' => __( 'Staff', 'agrilife' ),
		);

		// Post type arguments
		$args = array(
			'labels' => $labels,
			'public' => true,
			'show_ui' => true,
			'rewrite' => true,
			'supports' => array( 'thumbnail' ),
		);

		// Register the Staff post type
		register_post_type( 'staff', $args );

	}

	public static function get_instance() {

		return self::$instance;
		
	}

}
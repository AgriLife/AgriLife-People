<?php

class ALS_Taxonomy {

	/**
	 * Builds and registers the custom taxonomy
	 */
	public function __construct() {

		self::$instance = $this;

		// Taxonomy labels
		$labels = array(
			'name' => __( 'Types', 'agrilife' ),
			'singular_name' => __( 'Type', 'agrilife' ),
			'search_items' => __( 'Search Types', 'agrilife' ),
			'all_items' => __( 'All Types', 'agrilife' ),
			'parent_item' => __( 'Parent Type', 'agrilife' ),
			'parent_item_colon' => __( 'Parent Type:', 'agrilife' ),
			'edit_item' => __( 'Edit Type', 'agrilife' ),
			'update_item' => __( 'Update Type', 'agrilife' ), 
			'add_new_item' => __( 'Add New Type', 'agrilife' ),
			'new_item_name' => __( 'New Type Name', 'agrilife' ),
			'menu_name' => __( 'Types', 'agrilife' ),
		);

		// Taxonomy arguments
		$args = array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'show_admin_column' => true,
			'rewrite' => array( 'slug' ),
		);

		// Register the Type taxonomy
		register_taxonomy( 'types', 'staff', $args );

	}

}
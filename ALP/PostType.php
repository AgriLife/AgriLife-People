<?php

class ALP_PostType {

	/**
	 * Builds and registers the staff custom post type
	 */
	public function __construct() {

		// Backend labels
		$labels = array(
			'name' => __( 'People', 'agrilife' ),
			'singular_name' => __( 'People', 'agrilife' ),
			'add_new' => __( 'Add New', 'agrilife' ),
			'add_new_item' => __( 'Add New Person', 'agrilife' ),
			'edit_item' => __( 'Edit Person', 'agrilife' ),
			'new_item' => __( 'New Person', 'agrilife' ),
			'view_item' => __( 'View Person', 'agrilife' ),
			'search_items' => __( 'Search People', 'agrilife' ),
			'not_found' => __( 'No People Found', 'agrilife' ),
			'not_found_in_trash' => __( 'No People found in trash', 'agrilife' ),
			'parent_item_colon' => '',
			'menu_name' => __( 'People', 'agrilife' ),
		);

		// Post type arguments
		$args = array(
			'labels' => $labels,
			'public' => true,
			'show_ui' => true,
			'rewrite' => array( 'slug' => 'people' ),
			'supports' => false,
			'has_archive' => true,
		);

		// Register the People post type
		register_post_type( 'people', $args );

	}

}
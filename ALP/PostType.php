<?php

/**
 * Builds and registers the staff custom post type
 */

class ALP_PostType {

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
			'menu_icon' => 'dashicons-groups',
		);

		// Register the People post type
		register_post_type( 'people', $args );

		// Set the title as Last, First
    add_action( 'save_post', array( $this, 'save_people_title' ) );

	}

	/**
	 * Saves the title as Last, First
	 * @param  int $post_id The current post ID
	 * @return void
	 */
  public function save_people_title( $post_id ) {

    $slug = 'people';

    if ( ! isset( $_POST['post_type'] ) || $slug != $_POST['post_type'] )
      return;

    remove_action( 'save_post', array( $this, 'save_people_title' ) );

    $people_title = sprintf( '%s, %s',
    	get_field( 'ag-people-last-name', $post_id ),
    	get_field( 'ag-people-first-name', $post_id )
    );

    $people_slug = sanitize_title( $people_title );

    $args = array(
      'ID' => $post_id,
      'post_title' => $people_title,
      'post_name' => $people_slug,
    );

    wp_update_post( $args );

    add_action( 'save_post', array( $this, 'save_people_title' ) );

  }

}
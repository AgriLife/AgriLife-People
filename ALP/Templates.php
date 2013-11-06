<?php

/**
 * Redirects to correct template files based on query variables.
 * Also provides static methods to pull certain views
 */

class ALP_Templates {

	public function __construct() {

		add_filter( 'archive_template', array( $this, 'get_archive_template' ) );
		add_filter( 'search_template', array( $this, 'get_search_template' ) );
		add_filter( 'single_template', array( $this, 'get_single_template' ) );
		add_filter( 'taxonomy_template', array( $this, 'get_types_template' ) );

	}

	/**
	 * Shows the archive template when needed
	 * @param  string $archive_template The default archive template
	 * @return string                   The correct archive template
	 */
	public function get_archive_template( $archive_template ) {

		global $post;

		if ( is_post_type_archive( 'people' ) ) {
			$archive_template = PEOPLE_PLUGIN_DIR_PATH . '/archive-people.php';
		}

		return $archive_template;

	} 

	/**
	 * Shows the search template when needed
	 * @param  string $search_template The default search template
	 * @return string                  The correct search template
	 */
	public function get_search_template( $search_template ) {

		global $post;

		if ( get_query_var( 'post_type' ) == 'people' ) {
			$search_template = PEOPLE_PLUGIN_DIR_PATH . 'search-people.php';
		}

		return $search_template;

	} 

	/**
	 * Shows the single template when needed
	 * @param  string $single_template The default single template
	 * @return string                  The correct single template
	 */
	public function get_single_template( $single_template ) {

		global $post;

		if ( get_query_var( 'post_type' ) == 'people' ) {
			$single_template = PEOPLE_PLUGIN_DIR_PATH . '/single-people.php';
		}

		return $single_template;

	} 

	/**
	 * Shows the taxonomy archive when needed
	 * @param  string $types_template The default taxonomy archive
	 * @return string                 The correct taxonomy archive
	 */
	public function get_types_template( $types_template ) {

		global $post;

		if ( get_query_var( 'taxonomy' ) == 'types' ) {
			$types_template = PEOPLE_PLUGIN_DIR_PATH . '/taxonomy-types.php';
		}

		return $types_template;

	}

	/**
	 * Allows for easy inclusion of the search bar
	 * @return void
	 */
	public static function search_form() {

		require PEOPLE_PLUGIN_DIR_PATH . '/views/people-search.php';

	}

}
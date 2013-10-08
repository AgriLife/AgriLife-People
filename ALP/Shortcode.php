<?php

class ALP_Shortcode {

	public function __construct() {

		add_shortcode( 'people_listing', array( $this, 'create_shortcode' ) );

	}

	/**
	 * The shortcode logic
	 */
	public function create_shortcode() {

		global $post;

		query_posts( '&post_type=people&post_status=publish&posts_per_page=-1&meta_key=als_last-name&orderby=meta_value&order=ASC' ); 
		include( PEOPLE_PLUGIN_DIR_PATH . 'loop-people.php' );

	}

}
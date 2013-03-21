<?php

class ALS_Shortcode {

	public function __construct() {

		add_shortcode( 'staff_listing', array( $this, 'create_shortcode' ) );

	}

	/**
	 * The shortcode logic
	 */
	public function create_shortcode() {

		global $post;

		query_posts( '&post_type=staff&post_status=publish&posts_per_page=-1&meta_key=als_last-name&orderby=meta_value&order=ASC' ); 
		include( STAFF_PLUGIN_DIR_PATH . 'loop-staff.php' );

	}

}
<?php

/**
 * Creates the shortcode to list people. Can be filtered by Type taxonomy
 */

class ALP_Shortcode {

	public function __construct() {

		add_shortcode( 'people_listing', array( $this, 'create_shortcode' ) );

	}

	/**
	 * Renders the 'people_listing' shortcode
	 * @param  string $atts The shortcode attributes
	 * @return string       The shortcode output
	 */
	public function create_shortcode( $atts ) {

		// Pull in shortcode attributes and set defaults
		$atts = shortcode_atts( array(
							'type'   		=> false,
							'search' 		=> true,
							'lastnamefirst' => false,
							'order'         => 'ASC',
							'orderby'       => 'title',
						), $atts , 'people_listing' );

		// Sanitize shortcode attributes
		$type 			= ( $atts['type'] ? sanitize_text_field( $atts['type'] ) : false);
		$search 		= filter_var( $atts['search'], FILTER_VALIDATE_BOOLEAN );
		$lastnamefirst 	= filter_var( $atts['lastnamefirst'], FILTER_VALIDATE_BOOLEAN );
		$order  		= sanitize_text_field( $atts['order'] );
		$orderby		= sanitize_text_field( $atts['orderby'] );

		$people = ALP_Query::get_people( $type, $search, $order, $orderby );

		ob_start();
		if ( $search ) {
			ALP_Templates::search_form();
		}

		require PEOPLE_PLUGIN_DIR_PATH . '/views/people-list.php';
		
		$output = ob_get_contents();
		ob_clean();

		return $output;

	}

}
<?php

/**
 * Creates the shortcode to list people. Can be filtered by Type taxonomy
 */

class ALP_Shortcode {

	public function __construct() {

		add_shortcode( 'people_listing', array( $this, 'create_shortcode' ) );

	}

	/**
	 * The shortcode logic
	 */
	public function create_shortcode( $atts ) {

		extract( shortcode_atts( array(
							'type'   => false,
							'search' => true,
						),
						$atts ));

		$people = ALP_Query::get_people( $type );

		// The search parameter is passed as a string. Convert it to boolean.
		$search = $search === 'false' ? false : $search;

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
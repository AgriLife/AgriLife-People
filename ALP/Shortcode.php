<?php

class ALP_Shortcode {

	public function __construct() {

		add_shortcode( 'people_listing', array( $this, 'create_shortcode' ) );

	}

	/**
	 * The shortcode logic
	 */
	public function create_shortcode( $atts ) {

		extract( shortcode_atts( array(
							'type' => false,
						),
						$atts ));

		$people = ALP_Query::get_people( $type );

		ob_start();
		require PEOPLE_PLUGIN_DIR_PATH . '/views/people-list.php';
		$output = ob_get_contents();
		ob_clean();

		return $output;

	}

}
<?php

class ALP_Shortcode {

	public function __construct() {

		add_shortcode( 'people_listing', array( $this, 'create_shortcode' ) );

	}

	/**
	 * The shortcode logic
	 */
	public function create_shortcode( $atts ) {

		global $post;

		extract( shortcode_atts( array(
							'type' => '',
						),
						$atts ));

		$args = array(
			'post_type'      => 'people',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'meta_key'       => 'ag-people-last-name',
			'order_by'       => 'meta_value',
			'order'          => 'ASC'
		);

		if ( ! empty( $type ) ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'types',
					'field'    => 'slug',
					'terms'    => $type,
				),
			);
		}

		query_posts( $args ); 
		include( PEOPLE_PLUGIN_DIR_PATH . 'loop-people.php' );

	}

}
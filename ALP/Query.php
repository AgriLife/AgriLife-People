<?php

class ALP_Query {

	public static function get_people( $type = '' ) {

		$args = array(
			'post_type'      => 'people',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'       => 'title',
			'order'          => 'ASC'
		);

		// Add the person type query if needed
		if ( ! empty( $type ) ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'types',
					'field'    => 'slug',
					'terms'    => $type,
				),
			);
		}

		return new WP_Query( $args );

	}

}
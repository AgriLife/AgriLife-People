<?php

/**
 * Static methods for often-used queries
 */

class ALP_Query {

	/**
	 * Queries for people with some smart defaults
	 * @param  string $type   The type taxonomy slug to filter (optional)
	 * @param  string $search The search term (optional)
	 * @return object         A WP_Query object with the results
	 */
	public static function get_people( $type = '', $search = '', $orderby = 'title', $order = 'ASC' ) {

		// Set default arguments for every People query
		$args = array(
			'post_type'      => 'people',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'       => $orderby,
			'order'          => $order
		);

		// Add the person type query if needed
		if ( ! empty( $type ) ) {
			$slugs = explode(',', $type);
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'types',
					'field'    => 'slug',
					'terms'    => $slugs,
				),
			);
		}

		// Add the search terms if needed
		if ( !empty( $search ) && !in_array($search, array('true','false')) ) {
			$args['s'] = $search;
		}

		$args = apply_filters( 'people_listing_args', $args );

		return new WP_Query( $args );

	}

}

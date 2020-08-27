<?php
/**
 * The file that renders the person listing for an archive or search page.
 *
 * @link       https://github.com/AgriLife/agrilife-people/blob/master/views/people-list.php
 * @since      0.6.0
 * @package    agrilife-people
 * @subpackage agrilife-people/views
 */

if ( $people->have_posts() ) : ?>

	<ul class="people-listing-ul">
	<?php

	while ( $people->have_posts() ) :
		$people->the_post();

		$image = get_field( 'ag-people-photo' );
		if ( ! $image ) {
			$image = array();
		}
		if ( array_key_exists( 'sizes', $image ) && array_key_exists( 'people_archive', $image['sizes'] ) ) {
			$image_src = $image['sizes']['people_archive'];
			$image_alt = the_title( '', '', false );
		} else {
			$image_src = PEOPLE_PLUGIN_DIR_URL . 'img/agrilife-default-people-image-single.png';
			$image_alt = 'No photo found';
		}

		$permalink       = get_the_permalink();
		$post_title      = the_title( '', '', false );
		$job_title       = get_field( 'ag-people-title' );
		$phone           = get_field( 'ag-people-phone' );
		$email           = get_field( 'ag-people-email' );
		$office_location = get_field( 'ag-people-office-location' );
		$assistants      = get_field( 'ag-people-assistants' );

		if ( isset( $lastnamefirst ) && true === $lastnamefirst ) {
			$fullname = get_field( 'ag-people-last-name' ) . ', ' . get_field( 'ag-people-first-name' );
		} else {
			$fullname = get_field( 'ag-people-first-name' ) . ' ' . get_field( 'ag-people-last-name' );
		}

		$markup = array();

		$markup['photo'] = "<img src=\"{$image_src}\" alt=\"{$image_alt}\" title=\"{$image_alt}\" width=\"70\" height=\"70\" />";

		$markup['photo'] = apply_filters( 'ag-people-list-photo', $markup['photo'], $image, $image_src ); //phpcs:ignore

		$markup['photo-wrap'] = "<div class=\"people-image\"><a href=\"{$permalink}\" rel=\"bookmark\">
					{$markup['photo']}
				</a></div>";

		$markup['name'] = "<h3 class=\"people-name\" title=\"{$post_title}\"><a href=\"{$permalink}\">{$fullname}</a></h3>";

		$markup['title'] = "<h4 class=\"people-title\">{$job_title}</h4>";

		$markup['name-title'] = "<div class=\"people-head\">
					{$markup['name']}
					{$markup['title']}
				</div>";

		$markup['contact-phone'] = "<p class=\"people-phone tel\">{$phone}</p>";

		$markup['contact-email'] = "<p class=\"people-email email\"><a href=\"mailto:{$email}\">{$email}</a></p>";

		$markup['office-location'] = '';
		if ( get_field( 'ag-people-office-location' ) ) {
			$markup['office-location'] = "<p class=\"people-office-location\">{$office_location}</p>";
		}

		$markup['assistants'] = '';

		if ( $assistants ) {

			foreach ( $assistants as $assistant ) {
				// Ensure the item has non-empty values.
				$assistant = array_filter( $assistant );

				if ( ! empty( $assistant ) ) {

					$name = sprintf( '%s %s', $assistant['first_name'], $assistant['last_name'] );

					if ( array_key_exists( 'email', $assistant ) ) {
						$name = sprintf(
							'<a href="mailto:%s">%s</a>',
							$assistant['email'],
							$name
						);
					}

					$markup['assistants'] = sprintf(
						'<p class="people-assistants">%s: %s</p>',
						$assistant['title'],
						$name
					);
				}
			}
		}

		$markup['contact-details'] = "<div class=\"people-contact-details\">{$markup['contact-phone']}
{$markup['contact-email']}{$markup['office-location']}{$markup['assistants']}</div>";

		$listing = "{$markup['photo-wrap']}{$markup['name-title']}{$markup['contact-details']}";

		$listing = apply_filters( 'ag-people-list-item', $listing, $markup ); //phpcs:ignore

		$listing = '<li class="people-listing-item"><div class="role people-container">' . $listing . '</div></li>';

		echo wp_kses_post( $listing );

	endwhile;

	?>
	</ul>

	<?php
endif;

<?php
/**
 * The file that initializes the People custom post type
 *
 * A class definition that registers custom post types with their attributes
 *
 * @link       https://github.com/AgriLife/agrilife-people/blob/master/ALP/class-posttype.php
 * @since      0.1.0
 * @package    agrilife-people
 * @subpackage agrilife-people/ALP
 */

/**
 * The post type registration class
 *
 * @since 0.1.0
 * @return void
 */
class PostType {

	/**
	 * Builds and registers the custom post type.
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function __construct() {

		// Backend labels.
		$labels = array(
			'name'               => __( 'People', 'agrilife' ),
			'singular_name'      => __( 'People', 'agrilife' ),
			'add_new'            => __( 'Add New', 'agrilife' ),
			'add_new_item'       => __( 'Add New Person', 'agrilife' ),
			'edit_item'          => __( 'Edit Person', 'agrilife' ),
			'new_item'           => __( 'New Person', 'agrilife' ),
			'view_item'          => __( 'View Person', 'agrilife' ),
			'search_items'       => __( 'Search People', 'agrilife' ),
			'not_found'          => __( 'No People Found', 'agrilife' ),
			'not_found_in_trash' => __( 'No People found in trash', 'agrilife' ),
			'parent_item_colon'  => '',
			'menu_name'          => __( 'People', 'agrilife' ),
		);

		// Post type arguments.
		$args = array(
			'can_export'         => true,
			'capability_type'    => 'post',
			'has_archive'        => true,
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_in_rest'       => true,
			'show_in_menu'       => true,
			'show_in_admin_bar'  => true,
			'show_in_nav_menus'  => true,
			'show_ui'            => true,
			'rewrite'            => array( 'slug' => 'people' ),
			'supports'           => array( 'excerpt' ),
			'menu_icon'          => 'dashicons-groups',
			'hierarchical'       => true,
		);

		// Register the People post type.
		register_post_type( 'people', $args );

		// Set the title as Last, First.
		add_action( 'save_post', array( $this, 'save_people_title' ) );

		// Format search results output.
		add_filter( 'get_the_excerpt', array( $this, 'filter_site_search_results' ) );

		// Add post class to people page.
		add_filter( 'post_class', array( $this, 'rewrite_post_class' ), 10, 3 );

	}

	/**
	 * Saves the title as Last, First
	 *
	 * @param  int $post_id The current post ID.
	 * @return void
	 */
	public function save_people_title( $post_id ) {

		$type = get_post_type( $post_id );

		if ( 'people' === $type ) {

			$first_name = get_field( 'ag-people-first-name', $post_id );
			$last_name  = get_field( 'ag-people-first-name', $post_id );

			if ( ! empty( $first_name ) || ! empty( $last_name ) ) {

				remove_action( 'save_post', array( $this, 'save_people_title' ) );

				$people_title = sprintf(
					'%s, %s',
					$first_name,
					$last_name
				);

				$people_slug = sanitize_title( $people_title );

				$args = array(
					'ID'         => $post_id,
					'post_title' => $people_title,
					'post_name'  => $people_slug,
				);

				wp_update_post( $args );

				add_action( 'save_post', array( $this, 'save_people_title' ) );

			}
		}

	}

	/**
	 * Add post to People post classes
	 *
	 * @param array  $classes An array of post class names.
	 * @param string $class An array of additional class names added to the post.
	 * @param int    $post_id The post ID.
	 * @return array
	 */
	public function rewrite_post_class( $classes, $class, $post_id ) {

		if ( is_admin() ) {
			return $classes;
		}

		$index = array_search( 'people', $classes, true );

		if ( $index && ! array_search( 'post', $classes, true ) ) {
			$classes[] = 'post';
		}

		return $classes;

	}

	/**
	 * Format search result information
	 *
	 * @param string $excerpt The post excerpt.
	 * @return string
	 */
	public function filter_site_search_results( $excerpt ) {

		$type = get_post_type();
		if ( 'people' === $type ) {
			// People post types don't have an excerpt, so it's built here.
			// Based on single-people.php from AgriLife People plugin.
			$parts = array();

			$title = get_field( 'ag-people-title' );
			if ( $title ) {
				$parts[] = 'Title: ' . $title;
			}

			$office = get_field( 'ag-people-office-location' );
			if ( $office ) {
				$parts[] = 'Office: ' . $office;
			}

			$email = get_field( 'ag-people-email' );
			if ( $email ) {
				$parts[] = 'Email: ' . $email;
			}

			$phone = get_field( 'ag-people-phone' );
			if ( $phone ) {
				$parts[] = 'Phone: ' . $phone;
			}

			$resume = get_field( 'ag-people-resume' );
			if ( $resume ) {
				$parts[] = 'Resume/CV: ' . $resume;
			}

			$website = get_field( 'ag-people-website' );
			if ( $website ) {
				$parts[] = $website;
			}

			$undergrad_items = array();
			if ( get_field( 'ag-people-undergrad' ) ) {
				while ( has_sub_field( 'ag-people-undergrad' ) ) :
					$undergrad_items[] = get_sub_field( 'ag-people-undergrad-degree' );
					endwhile;
			}
			$undergrad = implode( ',', $undergrad_items );
			if ( ! empty( $undergrad ) ) {
				$parts[] = 'Undergraduate Education: ' . $undergrad;
			}

			$grad_items = array();
			if ( get_field( 'ag-people-graduate' ) ) {
				while ( has_sub_field( 'ag-people-graduate' ) ) :
					$grad_items[] = get_sub_field( 'ag-people-graduate-degree' );
					endwhile;
			}
			$grad = implode( ',', $grad_items );
			if ( ! empty( $grad ) ) {
				$parts[] = 'Graduate Education: ' . $grad;
			}

			$award_items = array();
			if ( get_field( 'ag-people-awards' ) ) {
				while ( has_sub_field( 'ag-people-awards' ) ) :
					$award_items[] = get_sub_field( 'ag-people-award' );
					endwhile;
			}
			$award = implode( ', ', $award_items );
			if ( ! empty( $award ) ) {
				$parts[] = 'Awards: ' . $award;
			}

			$taught_items = array();
			if ( get_field( 'ag-people-courses' ) ) {
				while ( has_sub_field( 'ag-people-courses' ) ) :
					$taught_items[] = get_sub_field( 'ag-people-course' );
					endwhile;
			}
			$taught = implode( ', ', $taught_items );
			if ( ! empty( $taught ) ) {
				$parts[] = 'Courses Taught: ' . $taught;
			}

			$content_items = array();
			while ( has_sub_field( 'ag-people-content' ) ) :
				$layout = get_row_layout();
				switch ( $layout ) {
					case 'ag-people-content-header':
						$content_items[] = get_sub_field( 'header' );
						break;
					case 'ag-people-content-text':
						$content_items[] = get_sub_field( 'text' );
						break;
					default:
						break;
				}
			endwhile;
			$content = implode( ', ', $content_items );
			if ( ! empty( $content ) ) {
				$parts[] = $content;
			}

			// Concatenate people data to string.
			$excerpt = implode( '; ', $parts );
			// Remove HTML.
			$excerpt = wp_strip_all_tags( $excerpt );
			// Combine all people data for excerpt.
			$excerpt = preg_replace( '/ ;/', ';', $excerpt );
			// Replace special and excess whitespace characters with normal spaces.
			$excerpt = str_replace( '&nbsp;', ' ', $excerpt );
			$excerpt = preg_replace( '/[\s\t\r\n\v]+/', ' ', $excerpt );
			$excerpt = preg_replace( '/[^\w\d,.;"\')]?\h\s/', ' ', $excerpt );
			// Restrict word count.
			$excerpt_length = apply_filters( 'excerpt_length', 50 );
			$excerpt_more   = apply_filters( 'excerpt_more', ' [&hellip;]' );
			$excerpt        = wp_trim_words( $excerpt, $excerpt_length, $excerpt_more );

		}

		return $excerpt;
	}

}

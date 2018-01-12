<?php

/**
 * Builds and registers the staff custom post type
 */

class ALP_PostType {

	public function __construct() {

		// Backend labels
		$labels = array(
			'name' => __( 'People', 'agrilife' ),
			'singular_name' => __( 'People', 'agrilife' ),
			'add_new' => __( 'Add New', 'agrilife' ),
			'add_new_item' => __( 'Add New Person', 'agrilife' ),
			'edit_item' => __( 'Edit Person', 'agrilife' ),
			'new_item' => __( 'New Person', 'agrilife' ),
			'view_item' => __( 'View Person', 'agrilife' ),
			'search_items' => __( 'Search People', 'agrilife' ),
			'not_found' => __( 'No People Found', 'agrilife' ),
			'not_found_in_trash' => __( 'No People found in trash', 'agrilife' ),
			'parent_item_colon' => '',
			'menu_name' => __( 'People', 'agrilife' ),
		);

		// Post type arguments
		$args = array(
			'labels' => $labels,
			'public' => true,
			'show_ui' => true,
			'rewrite' => array( 'slug' => 'people' ),
			'supports' => array( 'excerpt' ),
			'has_archive' => true,
			'menu_icon' => 'dashicons-groups',
		);

		// Register the People post type
		register_post_type( 'people', $args );

		// Set the title as Last, First
    add_action( 'save_post', array( $this, 'save_people_title' ) );

    // Format search results output
    add_filter( 'get_the_excerpt', array( $this, 'filter_site_search_results' ) );

    // Add post class to people page
    add_filter( 'post_class', array( $this, 'rewrite_post_class' ), 10, 3 );

	}

	/**
	 * Saves the title as Last, First
	 * @param  int $post_id The current post ID
	 * @return void
	 */
  public function save_people_title( $post_id ) {

    $slug = 'people';

    if ( ! isset( $_POST['post_type'] ) || $slug != $_POST['post_type'] )
      return;

    remove_action( 'save_post', array( $this, 'save_people_title' ) );

    $people_title = sprintf( '%s, %s',
    	get_field( 'ag-people-last-name', $post_id ),
    	get_field( 'ag-people-first-name', $post_id )
    );

    $people_slug = sanitize_title( $people_title );

    $args = array(
      'ID' => $post_id,
      'post_title' => $people_title,
      'post_name' => $people_slug,
    );

    wp_update_post( $args );

    add_action( 'save_post', array( $this, 'save_people_title' ) );

  }

	/**
	 * Add post to People post classes
	 * @param  $classes
	 * @param  $class
	 * @param  $post_id
	 * @return array
	 */
  public function rewrite_post_class( $classes, $class, $post_id ) {

    if (is_admin()){
    	return $classes;
    }

    if ( $index = array_search( 'people', $classes ) && !array_search( 'post', $classes ) ) {
      $classes[] = 'post';
    }

    return $classes;

  }

	/**
	 * Format search result information
	 * @param  $excerpt
	 * @return string
	 */
  public function filter_site_search_results( $excerpt ) {

    $type = get_post_type();
    if($type == 'people'){
      // People post types don't have an excerpt, so it's built here.
      // Based on single-people.php from AgriLife People plugin.
      $parts = array();

      $title = get_field( 'ag-people-title' );
      if($title)
        $parts[] = 'Title: ' . $title;

      $office = get_field( 'ag-people-office-location' );
      if($office)
        $parts[] = 'Office: ' . $office;

      $email = get_field( 'ag-people-email' );
      if($email)
        $parts[] = 'Email: ' . $email;

      $phone = get_field( 'ag-people-phone' );
      if($phone)
        $parts[] = 'Phone: ' . $phone;

      $resume = get_field( 'ag-people-resume' );
      if($resume)
        $parts[] = 'Resume/CV: ' . $resume;

      $website = get_field( 'ag-people-website' );
      if($website)
        $parts[] = $website;

      $undergrad_items = array();
      if ( get_field( 'ag-people-undergrad' ) ) {
          while ( has_sub_field( 'ag-people-undergrad' ) ) :
              $undergrad_items[] = get_sub_field( 'ag-people-undergrad-degree' );
          endwhile;
      }
      $undergrad = implode(',', $undergrad_items);
      if(!empty($undergrad))
        $parts[] = 'Undergraduate Education: ' . $undergrad;

      $grad_items = array();
      if ( get_field( 'ag-people-graduate' ) ) {
          while ( has_sub_field( 'ag-people-graduate' ) ) :
              $grad_items[] = get_sub_field( 'ag-people-graduate-degree' );
          endwhile;
      }
      $grad = implode(',', $grad_items);
      if(!empty($grad))
        $parts[] = 'Graduate Education: ' . $grad;

      $award_items = array();
      if ( get_field( 'ag-people-awards' ) ) {
          while ( has_sub_field( 'ag-people-awards' ) ) :
              $award_items[] = get_sub_field( 'ag-people-award' );
          endwhile;
      }
      $award = implode(', ', $award_items);
      if(!empty($award))
        $parts[] = 'Awards: ' . $award;

      $taught_items = array();
      if ( get_field( 'ag-people-courses' ) ) {
          while ( has_sub_field( 'ag-people-courses' ) ) :
              $taught_items[] = get_sub_field( 'ag-people-course' );
          endwhile;
      }
      $taught = implode(', ', $taught_items);
      if(!empty($taught))
        $parts[] = 'Courses Taught: ' . $taught;

      $content_items = array();
      while ( has_sub_field( 'ag-people-content' ) ) :
          $layout = get_row_layout();
          switch( $layout ) {
              case 'ag-people-content-header' :
                  $content_items[] = get_sub_field( 'header' );
                  break;
              case 'ag-people-content-text' :
                  $content_items[] = get_sub_field( 'text' );
                  break;
              default :
                  break;
          }
      endwhile;
      $content = implode( ', ', $content_items );
      if( !empty($content) )
        $parts[] = $content;

      // Concatenate people data to string
      $excerpt = implode( '; ', $parts );
      // Remove HTML
      $excerpt = wp_strip_all_tags( $excerpt );
      // Combine all people data for excerpt
      $excerpt = preg_replace( '/ ;/', ';', $excerpt );
      // Restrict word count
      $excerpt = wp_trim_words( $excerpt, 50 );

      // Replace special and excess whitespace characters with normal spaces
      $excerpt = str_replace( '&nbsp;', ' ', $excerpt );
      $excerpt = preg_replace( '/[\s\t\r\n\v]+/', ' ', $excerpt );
      $excerpt = preg_replace( '/[^\w\d,.;"\')]?\h\s/', ' ', $excerpt );

    }

    return $excerpt;
  }

}

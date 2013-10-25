<?php
/**
 * The Template for displaying 'staff' custom post type by taxonomy
 */

get_header(); ?>

	<div id="wrap">
	  	<div id="content" role="main">
	  	
	  		<h1 class="page-title"><?php
				printf( __( 'People: %s', 'agriflex' ), '<span>' . $wp_query->queried_object->name . '</span>' );
			?></h1>
	
			<?php
				$category_description = $wp_query->queried_object->description;
				if ( ! empty( $category_description ) )
					echo '<div class="archive-meta">' . $category_description . '</div>';

				/* Run the loop for the category page to output the posts.
				 * If you want to overload this in a child theme then include a file
				 * called loop-people.php and that will be used instead.
				 */

				// global $wp_query;

				$args = array(
					'posts_per_page' => '-1',
					// 'types' => $wp_query->queried_object->slug,
					'meta_key' => 'ag-people-last-name',
					'order_by' => 'meta_value',
					'order' => 'ASC',
				);

				query_posts( $query_string . '&posts_per_page=-1&meta_key=ag-people-last-name&order_by=meta_value&order=ASC' ); 
				include( PEOPLE_PLUGIN_DIR_PATH . 'loop-people.php' );
				?>	
	
	   </div><!-- #content -->
	
	</div><!-- #wrap -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>


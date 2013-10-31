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

				$people = ALP_Query::get_people( $wp_query->queried_object->slug );

				ob_start();
				require PEOPLE_PLUGIN_DIR_PATH . '/views/people-list.php';
				$output = ob_get_contents();
				ob_clean();

				echo $output;
				?>	
	
	   </div><!-- #content -->
	
	</div><!-- #wrap -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>


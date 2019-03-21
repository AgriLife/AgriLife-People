<?php

/**
 * The template for displaying people search results
 */

$search_terms = get_search_query();

get_header(); ?>

<div 
<?php

if ( ! function_exists( 'genesis_site_layout' ) ) {
	echo 'id="wrap"';
} else {
	echo 'class="' . genesis_site_layout() . '-wrap"';
}

?>
>
  <div 
	<?php

	if ( function_exists( 'genesis_site_layout' ) ) {
		echo 'class="content" ';
	}

	?>
  id="content" role="main">
		<h2 class="entry-title">Person search for: <?php echo $search_terms; ?></h2>
		<?php

		ALP_Templates::search_form();

		require_once PEOPLE_PLUGIN_DIR_PATH . '/ALP/Query.php';
		$people = ALP_Query::get_people( false, $search_terms );

		ob_start();
		require PEOPLE_PLUGIN_DIR_PATH . '/views/people-list.php';
		$output = ob_get_contents();
		ob_clean();

		echo $output;

		?>
	</div><!-- #content -->
	<?php

	if ( function_exists( 'genesis_site_layout' ) ) {
		get_sidebar();
	}

	?>
</div><!-- #wrap --><?php

if ( ! function_exists( 'genesis_site_layout' ) ) {
	// Not a genesis theme
	get_sidebar();
}

get_footer();

?>

<?php
/**
 * The template for displaying people search results
 *
 * @link       https://github.com/AgriLife/agrilife-people/blob/master/search-people.php
 * @since      0.1.0
 * @package    agrilife-people
 */

$search_terms = get_search_query();

get_header(); ?>

<?php
$open = '<div';
if ( ! function_exists( 'genesis_site_layout' ) ) {
	$open .= ' id="wrap"';
} else {
	$class = genesis_site_layout() . '-wrap';
	if ( 'agriflex4' === get_option( 'stylesheet' ) ) {
		$class .= ' grid-x grid-padding-x';
	}
	$open .= " class=\"$class\"";
}
$open .= '>';
echo wp_kses_post( $open );

?>
	<?php
	$content_open = '<div id="content" role="main"';
	if ( function_exists( 'genesis_site_layout' ) ) {
		$class = 'content';

		if ( 'agriflex4' === get_option( 'stylesheet' ) ) {
			$class .= ' cell small-12 medium-auto';
		}

		$content_open .= " class=\"$class\"";
	}

	$content_open .= '>';

	echo wp_kses_post( $content_open );

	$entrytitle = sprintf(
		'<h2 class="entry-title">Person search for: %s</h2>',
		$search_terms
	);
	echo wp_kses_post( $entrytitle );

	require_once PEOPLE_PLUGIN_DIR_PATH . '/ALP/class-alp-templates.php';
	ALP_Templates::search_form();

	require_once PEOPLE_PLUGIN_DIR_PATH . '/ALP/Query.php';
	$people = ALP_Query::get_people( false, $search_terms );

	ob_start();
	require PEOPLE_PLUGIN_DIR_PATH . '/views/people-list.php';
	$output = ob_get_contents();
	ob_clean();

	echo wp_kses_post( $output );

	?>
	</div><!-- #content -->
	<?php

	if ( function_exists( 'genesis_site_layout' ) ) {
		get_sidebar();
	}

	?>
</div><!-- #wrap --><?php

if ( ! function_exists( 'genesis_site_layout' ) ) {
	// Not a genesis theme.
	get_sidebar();
}

get_footer();

?>

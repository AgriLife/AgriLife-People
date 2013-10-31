<?php
/**
 * The Template for displaying all people single posts
 */


get_header(); ?>

<div id="wrap">
	<div id="content" role="main">
		<h1 class="entry-title">People</h1>
		<?php
		ALP_Templates::search_form();

		$people = ALP_Query::get_people();

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


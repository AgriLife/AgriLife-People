<?php
/**
 * The Template for displaying all people single posts
 */


get_header(); ?>

<div class="<?php

if( function_exists('genesis_site_layout') ){
	echo genesis_site_layout();
}

?>-wrap">
  <div <?php

  if( function_exists('genesis_site_layout') ){
    echo 'class="content" ';
  }

  ?>id="content" role="main">
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
	</div><!-- #content --><?php

	if( function_exists('genesis_site_layout') ){
		// Genesis theme
		get_sidebar();
	}

	?>
</div><!-- #wrap --><?php

if( !function_exists('genesis_site_layout') ){
	// Not a genesis theme
	get_sidebar();
}

get_footer();

?>


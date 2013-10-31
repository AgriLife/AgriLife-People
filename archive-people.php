<?php
/**
 * The Template for displaying all people single posts
 */


get_header(); ?>

<div id="wrap">
	<div id="content" role="main">
		<h1 class="entry-title">People</h1>
		<div class="people-search-form">
			<label>
				<h4>Search People Database</h4>
			</label>
			<form role="search" class="people-searchform" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
				<input type="text" class="s" name="s" id="s" placeholder="Wilber B. Snodgrass" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;"/><br />
				<input type="hidden" name="post_type" value="people" />
			</form>
		</div>

			<?php 

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


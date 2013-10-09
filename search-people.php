<?php

/**
 * The template for displaying staff search results
 */

$search_terms = get_search_query();

get_header(); ?>

<div id="wrap">
	<div id="content" role="main">
		<h1 class="entry-title">Staff search for: <?php echo $search_terms; ?></h1>
		<div class="staff-search-form">
			<label>
				<h4>Search Staff Database</h4>
			</label>
			<form role="search" class="searchform" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
				<input type="text" class="s" name="s" id="s" placeholder="<?php echo $search_terms; ?>" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;"/><br />
				<input type="hidden" name="post_type" value="staff" />
			</form>
		</div>

			<?php 

			include( STAFF_PLUGIN_DIR_PATH . 'loop-staff.php' );
			
			wp_reset_query(); ?>


	</div><!-- #content -->

</div><!-- #wrap -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>
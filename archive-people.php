<?php
/**
 * The Template for displaying all staff single posts
 */


get_header(); ?>

<div id="wrap">
	<div id="content" role="main">
		<h1 class="entry-title">Staff</h1>
		<div class="staff-search-form">
			<label>
				<h4>Search Staff Database</h4>
			</label>
			<form role="search" class="searchform" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
				<input type="text" class="s" name="s" id="s" placeholder="Wilber B. Snodgrass" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;"/><br />
				<input type="hidden" name="post_type" value="staff" />
			</form>
		</div>

			<?php 

			query_posts( '&post_type=staff&post_status=publish&posts_per_page=-1&meta_key=als_last-name&orderby=meta_value&order=ASC' ); 
			include( STAFF_PLUGIN_DIR_PATH . 'loop-staff.php' );
			
			wp_reset_query(); ?>


	</div><!-- #content -->

</div><!-- #wrap -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>


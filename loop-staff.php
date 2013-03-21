<?php
/**
 * The loop that displays posts.
 */
?>
	
<?php if ( ! have_posts() ) : ?>
	<div id="post-0" class="post error404 not-found">
		<h1 class="entry-title"><?php _e( 'Not Found', 'agriflex' ); ?></h1>
		<div class="entry-content">
			<p><?php _e( 'Apologies, but no jobs were found that match your search criteria.', 'agriflex' ); ?></p>
		</div><!-- .entry-content -->
	</div><!-- #post-0 -->
<?php endif; ?>


<ul class="staff-listing-ul">
	<?php	
	while (have_posts()) : the_post();
		global $post;
		?>
		<li class="staff-listing-item">
			<div class="role staff-container">
				<div class="staff-image">
					<a href="<?php the_permalink(); ?>" rel="bookmark">
					<?php if ( has_post_thumbnail() ) {
						the_post_thumbnail('staff_archive');
					} else  {
						echo '<img src="'.STAFF_PLUGIN_DIR_URL.'img/agrilife-default-staff-image-single.png" alt="AgriLife Logo" title="AgriLife" width="70" height="70" />';
					}
					?></a>
				</div>
				<div class="staff-head">
					<h2 class="staff-title" title="<?php the_title(); ?>"><a href="<?php the_permalink(); ?>"><?php echo rwmb_meta( 'als_first-name' ).' '.rwmb_meta( 'als_last-name' ); ?></a></h2>
					<h3 class="staff-position"><?php echo rwmb_meta( 'als_position' ); ?></h3>
				</div>                                  
				<div class="staff-contact-details">
					<p class="staff-phone tel"><?php echo rwmb_meta( 'als_phone' ); ?></p>
					<p class="staff-email email"><a href="mailto:<?php echo rwmb_meta( 'als_email' ); ?>"><?php echo rwmb_meta( 'als_email' ); ?></a></p>
				</div>
			</div>
			</a>
		</li>

	<?php endwhile;?>
</ul>

<?php wp_reset_query(); ?>

<?php
/**
 * The loop that displays posts.
 *
 * The loop displays the posts and the post content.  See
 * http://codex.wordpress.org/The_Loop to understand it and
 * http://codex.wordpress.org/Template_Tags to understand
 * the tags used in it.
 *
 * This can be overridden in child themes with loop.php or
 * loop-template.php, where 'template' is the loop context
 * requested by a template. For example, loop-index.php would
 * be used if it exists and we ask for the loop with:
 * <code>get_template_part( 'loop', 'index' );</code>
 *
 * Used in archive.php
 *
 * @package WordPress
 * @subpackage agriflex
 * @since agriflex 1.0
 */
?>
	

	<?php /* If there are no tests to display, let the user know */ ?>
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
				          <a href="<?php the_permalink(); ?>" rel="bookmark">
				          <?php if ( has_post_thumbnail() ) {
				                 the_post_thumbnail('staff_archive');
				          } else  {
				               echo '<img src="'.STAFF_PLUGIN_DIR_URL.'img/agrilife-default-staff-image-single.png" alt="AgriLife Logo" title="AgriLife" width="70" height="70" />';
				          }
				          ?></a>
				               <hgroup class="staff-head">
				               <h2 class="staff-title" title="<?php the_title(); ?>"><a href="<?php the_permalink(); ?>"><?php echo rwmb_meta( 'als_first-name' ).' '.rwmb_meta( 'als_last-name' ); ?></a></h2>
				               <h3 class="staff-position"><?php echo rwmb_meta( 'als_position' ); ?></h3>
				               </hgroup>                                  
				               <div class="staff-contact-details">
				                    <p class="staff-phone tel"><?php echo rwmb_meta( 'als_phone' ); ?></p>
				                    <p class="staff-email email"><a href="mailto:<?php echo rwmb_meta( 'als_email' ); ?>"><?php echo rwmb_meta( 'als_email' ); ?></a></p>
				               </div>
				          </div>
				          </a>
				     </li>
			
		<?php endwhile;?>
	</ul>
	
	<div class="navigation">
		<div class="alignleft"><?php next_posts_link('Previous entries') ?></div>
		<div class="alignright"><?php previous_posts_link('Next entries') ?></div>
	</div>
	<?php wp_reset_query(); ?>

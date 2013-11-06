<?php
/**
 * The Template for displaying all people single posts.
 */

get_header(); ?>

		<div id="wrap">
			<div id="content" role="main">
			<p><span class="read-more"><a href="../">&larr; All People</a><span></p>

<?php if ( have_posts() ) while ( have_posts() ) : the_post();

	if ( get_field( 'ag-people-photo' ) ) {
		$image = get_field( 'ag-people-photo' );
		$image_src = $image['sizes']['people_single'];
		$image_alt = the_title( '', '', false );
	} else {
		$image_src = PEOPLE_PLUGIN_DIR_URL . 'img/agrilife-default-people-image-single.png';
		$image_alt = 'No photo found';
	}

?>			
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>		
					<section class="entry-content">
						<div class="people-single-image">
							<img src="<?php echo $image_src; ?>" alt="<?php echo $image_alt; ?>" title="<?php echo $image_alt; ?>" width="100%" height="auto" />
						</div>
						<div class="people-person-details">
							<dl>	
							<dt class="name"><?php the_field( 'ag-people-first-name' ); ?> <?php the_field( 'ag-people-last-name' ); ?></dt>
							
								<dd class="role"><?php the_field( 'ag-people-title' );?></dd>
							
								<dd><?php the_field( 'ag-people-location' );?></dd> 
							
								<dd class="email"><a href="mailto:<?php the_field( 'ag-people-email' );?>"><?php the_field( 'ag-people-email' );?></a></dd>
							
								<dd><?php the_field( 'ag-people-phone' );?></dd> 

								<?php if ( get_field( 'ag-people-resume' ) ) : ?>
									<dd><a href="<?php the_field( 'ag-people-resume' ); ?>" target="_blank">Resume/CV</a></dd>
								<?php endif; ?>
								
								<dd class="website"><a href="<?php the_field( 'ag-people-website' );?>"><?php the_field( 'ag-people-website' );?></a></dd> 
							
							<?php
							if ( get_field( 'ag-people-undergrad' ) ) {
								echo '<dt>Undergraduate Education</dt>';
								while ( has_sub_field( 'ag-people-undergrad' ) ) :
									printf('<dd>%s</dd>', get_sub_field( 'ag-people-undergrad-degree' ) );
								endwhile;
							}
							?>

							<?php
							if ( get_field( 'ag-people-graduate' ) ) {
								echo '<dt>Graduate Education</dt>';
								while ( has_sub_field( 'ag-people-graduate' ) ) :
									printf( '<dd>%s</dd>', get_sub_field( 'ag-people-graduate-degree' ) );
								endwhile;
							}
							?>

							<?php
							if ( get_field( 'ag-people-awards' ) ) {
								echo '<dt>Awards</dt>';
								while ( has_sub_field( 'ag-people-awards' ) ) :
									printf( '<dd>%s</dd>', get_sub_field( 'ag-people-award' ) );
								endwhile;
							}
							?>

							<?php
							if ( get_field( 'ag-people-courses' ) ) {
								echo '<dt>Courses Taught</dt>';
								while ( has_sub_field( 'ag-people-courses' ) ) :
									printf( '<dd>%s</dd>', get_sub_field( 'ag-people-course' ) );
								endwhile;
							}
							?>

							</dl>	
						</div>

						<div class="people-person-content">
							<?php while ( has_sub_field( 'ag-people-content' ) ) :
								$layout = get_row_layout();
								switch( $layout ) {
									case 'ag-people-content-header' :
										printf( '<h3 class="people-content-header">%s</h3>', get_sub_field( 'header' ) );
										break;
									case 'ag-people-content-text' :
										printf( '<div class="people-content-text">%s</div>', get_sub_field( 'text' ) );
										break;
									case 'ag-people-content-image' :
										$image = get_sub_field( 'image' );
										$image_src = $image['url'];
										$image_title = $image['title'];
										$image_alt = $image['alt'];
										printf( '<div class="people-content-image"><img src="%s" alt="%s" title="%s" /></div>', $image_src, $image_src, $image_title );									
										break;
									case 'ag-people-content-gallery' :
										$images = get_sub_field( 'gallery' );
										$image_ids = array();
										foreach ( $images as $image ) {
											$image_ids[] = $image['id'];
										}
										$shortcode = sprintf( '[gallery ids="%s"]', implode(',', $image_ids ) );
										echo do_shortcode( $shortcode );
										break;
								}
							endwhile;
							?>
						</div>

						<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'agriflex' ), 'after' => '</div>' ) ); ?>
					</section><!-- .entry-content -->

	
					<footer class="entry-meta">

						<section class="entry-utility">
							<?php edit_post_link( __( 'Edit', 'agriflex' ), '<span class="edit-link">', '</span>' ); ?>
						</section><!-- .entry-utility -->
					</footer><!-- .entry-meta -->
				</article><!-- #post-<?php the_ID(); ?> -->

<?php endwhile; // end of the loop. ?>
			</div><!-- #content -->
		</div><!-- #wrap -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>

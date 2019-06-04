<?php
/**
 * The Template for displaying all people single posts.
 */

// Use site's default Genesis layout for single people page
add_filter( 'body_class', 'alp_single_people_layout' );

function alp_single_people_layout( $classes ) {

	if ( function_exists( 'genesis_site_layout' ) ) {

		$classes[] = genesis_site_layout();

	}

	return $classes;

}

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
		<p><span class="read-more"><a href="../">&larr; All People</a><span></p>

<?php
if ( have_posts() ) {
	while ( have_posts() ) :
		the_post();

		if ( get_field( 'ag-people-photo' ) ) {
			$image     = get_field( 'ag-people-photo' );
			$image_src = $image['sizes']['people_single'];
			$image_alt = the_title( '', '', false );
		} else {
			$image_src = PEOPLE_PLUGIN_DIR_URL . 'img/agrilife-default-people-image-single.png';
			$image_alt = 'No photo found';
		}

		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<h2 class="entry-title"><?php the_field( 'ag-people-first-name' ); ?> <?php the_field( 'ag-people-last-name' ); ?></h2>
			<section class="entry-content">
				<div class="people-single-head">
					<div class="people-single-image">
						<img src="<?php echo $image_src; ?>" alt="<?php echo $image_alt; ?>" title="<?php echo $image_alt; ?>" width="100%" height="auto" />
					</div>

					<div class="people-person-details">
						<dl class="details">
							<dt class="name"><?php the_field( 'ag-people-first-name' ); ?> <?php the_field( 'ag-people-last-name' ); ?></dt>

							<dd class="role"><?php the_field( 'ag-people-title' ); ?></dd>

							<?php if ( get_field( 'ag-people-office-location' ) ) : ?>
							<dt class="field-title">Office: </dt>
							<dd class="office"><?php the_field( 'ag-people-office-location' ); ?></dd>
								<?php
							endif;

							if ( get_field( 'ag-people-email' ) ) :
								?>
							<dt class="field-title">Email: </dt>
							<dd class="email"><a href="mailto:<?php the_field( 'ag-people-email' ); ?>"><?php the_field( 'ag-people-email' ); ?></a></dd>
								<?php
							endif;

							if ( get_field( 'ag-people-phone' ) ) :
								?>
							<dt class="field-title">Phone: </dt>
							<dd class="phone"><?php the_field( 'ag-people-phone' ); ?></dd>
								<?php
							endif;

							if ( get_field( 'ag-people-resume' ) ) :
								?>
							<dd class="resume"><a href="<?php the_field( 'ag-people-resume' ); ?>" target="_blank">Resume/CV</a></dd>
								<?php
							endif;

							if ( get_field( 'ag-people-website' ) ) :
								?>
							<dd class="website"><a href="<?php the_field( 'ag-people-website' ); ?>"><?php the_field( 'ag-people-website' ); ?></a></dd>
							<?php endif; ?>
						</dl>
						<?php

							$assistants = get_field( 'ag-people-assistants' );

						if ( $assistants ) {

							// Ensure no empty assistant arrays
							foreach ( $assistants as $key => $assistant ) {
								if ( is_array( $assistant ) ) {
									$assistants[ $key ] = array_filter( $assistant );
								} else {
									$assistants[ $key ] = $assistant;
								}
							}

							$assistants = array_filter( $assistants );

							if ( ! empty( $assistants ) ) {

								echo '<div class="people-person-assistants">';

								foreach ( $assistants as $assistant ) {

									$name = sprintf( '%s %s', $assistant['first_name'], $assistant['last_name'] );

									if ( array_key_exists( 'email', $assistant ) ) {
										$name = sprintf(
											'<a href="mailto:%s">%s</a>',
											$assistant['email'],
											$name
										);
									}

									echo sprintf(
										'<p class="people-assistants">%s: %s</p>',
										$assistant['title'],
										$name
									);
								}

								echo '</div>';

							}
						}
						?>
					</div>

					<dl class="education">
						<?php
						if ( get_field( 'ag-people-undergrad' ) ) {
							echo '<dt>Undergraduate Education</dt>';
							while ( has_sub_field( 'ag-people-undergrad' ) ) :
								printf( '<dd>%s</dd>', get_sub_field( 'ag-people-undergrad-degree' ) );
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


					<div class="people-person-content">
						<?php
						while ( has_sub_field( 'ag-people-content' ) ) :
							$layout = get_row_layout();
							switch ( $layout ) {
								case 'ag-people-content-header':
									printf( '<h3 class="people-content-header">%s</h3>', get_sub_field( 'header' ) );
									break;
								case 'ag-people-content-text':
									printf( '<div class="people-content-text">%s</div>', get_sub_field( 'text' ) );
									break;
								case 'ag-people-content-image':
									$image       = get_sub_field( 'image' );
									$image_src   = $image['url'];
									$image_title = $image['title'];
									$image_alt   = $image['alt'];
									printf( '<div class="people-content-image"><img src="%s" alt="%s" title="%s" /></div>', $image_src, $image_src, $image_title );
									break;
								case 'ag-people-content-gallery':
									$images    = get_sub_field( 'gallery' );
									$image_ids = array();
									foreach ( $images as $image ) {
										$image_ids[] = $image['id'];
									}
									$shortcode = sprintf( '[gallery ids="%s"]', implode( ',', $image_ids ) );
									echo do_shortcode( $shortcode );
									break;
							}
						endwhile;
						?>
					</div>

				<?php
				wp_link_pages(
					array(
						'before' => '<div class="page-link">' . __( 'Pages:', 'agriflex' ),
						'after'  => '</div>',
					)
				);
				?>
			</section><!-- .entry-content -->

		</article><!-- #post-<?php the_ID(); ?> -->

		<?php
endwhile;
}; // end of the loop.
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

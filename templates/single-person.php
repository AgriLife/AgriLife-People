<?php
/**
 * The file that renders the person post type's single page template.
 *
 * @link       https://github.com/AgriLife/agrilife-people/blob/master/templates/single-person.php
 * @since      0.6.0
 * @package    agrilife-people
 * @subpackage agrilife-people/templates
 */

remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );

/**
 * Add All People link.
 *
 * @since 1.6.0
 * @return void
 */
function alp_all_people_link() {

	?><p><span class="read-more"><a href="../">&larr; All People</a><span></p>
	<?php

}
add_action( 'genesis_before_entry', 'alp_all_people_link' );

/**
 * Empty the entry title wrapper if it's not on the AgriFlex4 theme.
 *
 * @since 1.6.0
 * @param string $open The opening tag.
 * @return string
 */
function alp_entry_title_open( $open ) {

	if ( 'agriflex4' !== get_option( 'stylesheet' ) ) {
		$open = '';
	}

	return $open;

}
add_filter( 'genesis_markup_entry-title_open', 'alp_entry_title_open' );

/**
 * Empty the entry title wrapper if it's not on the AgriFlex4 theme.
 *
 * @since 1.6.0
 * @param string $close The closing tag.
 * @return string
 */
function alp_entry_title_close( $close ) {

	if ( 'agriflex4' !== get_option( 'stylesheet' ) ) {
		$close = '';
	}

	return $close;

}
add_filter( 'genesis_markup_entry-title_close', 'alp_entry_title_close' );

/**
 * Add All People link.
 *
 * @since 1.6.0
 * @param string $title The post title.
 * @return string
 */
function alp_post_title( $title ) {

	$title = get_field( 'ag-people-first-name' ) . ' ' . get_field( 'ag-people-last-name' );

	return $title;

}
add_filter( 'genesis_post_title_text', 'alp_post_title' );

/**
 * Display all person fields.
 *
 * @since 1.6.0
 * @return void
 */
function alp_post_content() {

	if ( get_field( 'ag-people-photo' ) ) {
		$image     = get_field( 'ag-people-photo' );
		$image_src = $image['sizes']['people_single'];
		$image_alt = the_title( '', '', false );
	} else {
		$image_src = PEOPLE_PLUGIN_DIR_URL . 'img/agrilife-default-people-image-single.png';
		$image_alt = 'No photo found';
	}

	?>
	<div class="people-single-head grid-x grid-padding-x">
		<div class="people-single-image cell small-4 medium-shrink">
			<img src="<?php echo esc_attr( $image_src ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>" title="<?php echo esc_attr( $image_alt ); ?>" width="100%" height="auto" />
		</div>
		<div class="people-person-details cell small-8 medium-auto">
			<dl class="details">
				<dt class="name">
				<?php
					genesis_entry_header_markup_open();
					genesis_do_post_title();
					genesis_entry_header_markup_close();
				?>
				</dt>

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

				// Ensure no empty assistant arrays.
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
							wp_kses_post( $assistant['title'] ),
							wp_kses_post( $name )
						);
					}

					echo '</div>';

				}
			}
			?>
		</div>

		<dl class="education cell small-12 medium-12">
			<?php
			if ( get_field( 'ag-people-undergrad' ) ) {
				echo '<dt>Undergraduate Education</dt>';
				while ( has_sub_field( 'ag-people-undergrad' ) ) :
					printf( '<dd>%s</dd>', wp_kses_post( get_sub_field( 'ag-people-undergrad-degree' ) ) );
				endwhile;
			}
			?>

			<?php
			if ( get_field( 'ag-people-graduate' ) ) {
				echo '<dt>Graduate Education</dt>';
				while ( has_sub_field( 'ag-people-graduate' ) ) :
					printf( '<dd>%s</dd>', wp_kses_post( get_sub_field( 'ag-people-graduate-degree' ) ) );
				endwhile;
			}
			?>

			<?php
			if ( get_field( 'ag-people-awards' ) ) {
				echo '<dt>Awards</dt>';
				while ( has_sub_field( 'ag-people-awards' ) ) :
					printf( '<dd>%s</dd>', wp_kses_post( get_sub_field( 'ag-people-award' ) ) );
				endwhile;
			}
			?>

			<?php
			if ( get_field( 'ag-people-courses' ) ) {
				echo '<dt>Courses Taught</dt>';
				while ( has_sub_field( 'ag-people-courses' ) ) :
					printf( '<dd>%s</dd>', wp_kses_post( get_sub_field( 'ag-people-course' ) ) );
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
						printf( '<h3 class="people-content-header">%s</h3>', wp_kses_post( get_sub_field( 'header' ) ) );
						break;
					case 'ag-people-content-text':
						printf( '<div class="people-content-text">%s</div>', wp_kses_post( get_sub_field( 'text' ) ) );
						break;
					case 'ag-people-content-image':
						$image       = get_sub_field( 'image' );
						$image_src   = $image['url'];
						$image_title = $image['title'];
						$image_alt   = $image['alt'];
						printf( '<div class="people-content-image"><img src="%s" alt="%s" title="%s" /></div>', esc_attr( $image_src ), esc_attr( $image_src ), esc_attr( $image_title ) );
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

}
add_action( 'the_content', 'alp_post_content' );

get_header();

genesis();

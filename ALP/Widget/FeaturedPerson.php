<?php

class ALP_Widget_FeaturedPerson extends WP_Widget {

	/**
	 * Register the widget with WordPress
	 */
	public function __construct() {

		$widget_details = array(
			'description' => __( 'Feature a person on the sidebar', 'agriflex' ),
		);

		parent::__construct(
			'featured_person', // Base ID
			__( 'Featured Person', 'agriflex' ), // Display Name
			$widget_details
		);

	}

	/**
	 * Front-end display of the widget
	 */
	public function widget( $args, $instance ) {

		$title = apply_filters( 'widget_title', $instance['title'] );

		$person_id = $instance['people'];

		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];

		$image = get_field('ag-people-photo', $person_id );

		$image_url = $image['sizes']['people_archive'];
		$image_alt = $image['alt'];

		ob_start(); ?>

			<div class="featured-person-image">
				<img src="<?php echo $image_url; ?>" alt="<?php echo $image_alt; ?>" />
			</div>

			<div class="featured-person-info">
				<h4 class="featured-person-name">
					<?php the_field( 'ag-people-first-name', $person_id ); ?> <?php the_field( 'ag-people-last-name', $person_id ); ?>
				</h4>
				<p class="featured-person-title"><?php the_field( 'ag-people-title', $person_id ); ?></p>
				<p class="feature-person-blurb">
					<?php echo $instance['blurb']; ?>
				</p>
				<p class="featured-person-more">
					<a href="<?php echo get_permalink( $person_id ); ?>">Read More &rarr;</a>
				</p>
			</div>

		<?php
		$output = ob_get_contents();
		ob_clean();

		echo $output;

		echo $args['after_widget'];

	}

	/**
	 * Back-end widget form
	 */
	public function form( $instance ) {

		if ( isset( $instance['title'] ) ) {
			$title = $instance['title'];
		} else {
			$title = __( 'Featured Person', 'agriflex' );
		}

		$title_field_id = $this->get_field_id( 'title' );
		$title_field_name = $this->get_field_name( 'title' );

		$people = $this->get_people_list();
		$people_field_id = $this->get_field_id( 'people' );
		$people_field_name = $this->get_field_name( 'people' );

		if ( isset( $instance['blurb'] ) )
			$person_blurb = $instance['blurb'];
		$person_blurb = $instance['blurb'];
		$person_blurb_id = $this->get_field_id( 'blurb' );
		$person_blurb_name = $this->get_field_name( 'blurb' );

		ob_start();?>

		<p>
			<label for="<?php echo $title_field_id; ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $title_field_id; ?>" name="<?php echo $title_field_name; ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>

		<p>
			<label for="<?php echo $people_field_id; ?>"><?php _e( 'Person:' ); ?></label>
			<select id="<?php echo $people_field_id; ?>" name="<?php echo $people_field_name; ?>">
				<option value="" <?php selected( $instance['people'], '' ); ?>><?php _e( 'Select a Person' ); ?></option>
				<?php foreach ( $people as $person ) : ?>
					<option value="<?php echo $person->ID; ?>" <?php selected( $instance['people'], $person->ID ); ?>><?php echo $person->post_title; ?></option>
				<?php endforeach; ?>
			</select>
		</p>

		<p>
			<label for="<?php echo $person_blurb_id; ?>"><?php _e( 'Blurb:' ); ?></label>
			<textarea name="<?php echo $person_blurb_name; ?>" id="<?php echo $person_blurb_id; ?>" cols="29" rows="10"><?php echo $person_blurb; ?></textarea>
		</p>

		<?php
		$form = ob_get_contents();
		ob_clean();

		echo $form;

	}

	/**
	 * Save widget values
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = array();

		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		$instance['people'] = ( ! empty( $new_instance['people'] ) ) ? strip_tags( $new_instance['people'] ) : '';

		$instance['blurb'] = ( ! empty( $new_instance['blurb'] ) ) ? strip_tags( $new_instance['blurb'] ) : '';

		return $instance;

	}

	private function get_people_list() {

		global $wpdb;

		$people_query = $wpdb->get_results( "SELECT ID, post_title FROM $wpdb->posts WHERE post_type = 'people' AND post_status = 'publish'" );

		return $people_query;

	}

}
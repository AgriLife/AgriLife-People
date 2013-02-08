<?php

class ALS_Upgrade {

	private static $instance;

	private $version;

	private $option_name = 'als_options';

	private $options = null;

	private $defaults = array(
												'version' => '',
											);

	private $meta_name_old = '_my_meta';

	private $meta_name_new = META_PREFIX;

	public function __construct() {

		self::$instance = $this;

		$this->version = AgriLife_Staff::get_instance()->version;

		$this->defaults['version'] = $this->version;

		// if ( $this->check_new_install() === false ) {
			$this->update_staff_meta();
		// } else {
		// 	$this->set_staff_options();
		// }

	}

	private function check_new_install() {

		// Return if this has already run
		if ( isset( $this->options ) )
			return;

		// Get the plugin options from the database
		$options = get_option( $this->option_name );

		// Return false if the options don't exist
		if ( $options == false )
			return false;

		return;

	} 

	private function update_staff_meta() {

		// Get existing staff posts
		$staff = $this->get_staff();

		foreach ( $staff as $s ) {
			$this->migrate( $s );
		}

	}

	private function get_staff() {

		$args = array(
			'post_type' => 'staff',
			'post_status' => 'publish',
		);

		$staff = get_posts( $args );

		return $staff;

	}

	private function migrate( $s ) {

		$old_meta = get_post_meta( $s->ID, $this->meta_name_old );

		foreach ( $old_meta[0] as $k => $v ) {

			switch ( $k ) {
				case 'firstname' :
					$this->convert_firstname( $s->ID, $v );
					break;
				case 'lastname' :
					$this->convert_lastname( $s->ID, $v );
					break;
				case 'position' :
					$this->convert_position( $s->ID, $v );
					break;
				case 'room' :
					$this->convert_room( $s->ID, $v );
					break;
				case 'website' :
					$this->convert_website( $s->ID, $v );
					break;
				case 'phone' :
					$this->convert_phone( $s->ID, $v );
					break;
				case 'email' :
					$this->convert_email( $s->ID, $v );
					break;
				case 'undergraduate_1' :
				case 'undergraduate_2' :
					$this->convert_undergraduate( $s->ID, $v );
					break;
				case 'graduate_1' :
				case 'graduate_2' :
				case 'graduate_3' :
					$this->convert_graduate( $s->ID, $v );
					break;
				case 'specialty' :
					$this->convert_specialty( $s->ID, $v );
					break;
				case 'research' :
					$this->convert_description( $s->ID, $v );
					break;
				case 'award_1' :
				case 'award_2' :
				case 'award_3' :
					$this->convert_award( $s->ID, $v );
					break;
				case 'course_1' :
				case 'course_2' :
				case 'course_3' :
				case 'course_4' :
				case 'course_5' :
					$this->convert_course( $s->ID, $v );
					break;
			}

		}

	}

	private function convert_firstname( $id, $value ) {

		update_post_meta( $id, 'als_first-name', $value );

	}

	private function convert_lastname( $id, $value ) {

		update_post_meta( $id, 'als_last-name', $value );

	}

	private function convert_position( $id, $value ) {

		update_post_meta( $id, 'als_position', $value );

	}

	private function convert_room( $id, $value ) {

		update_post_meta( $id, 'als_building-room', $value );

	}

	private function convert_website( $id, $value ) {

		update_post_meta( $id, 'als_website', $value );

	}

	private function convert_phone( $id, $value ) {

		update_post_meta( $id, 'als_phone', $value );

	}

	private function convert_email( $id, $value ) {

		update_post_meta( $id, 'als_email', $value );

	}

	private function convert_undergraduate( $id, $value ) {

		$undergrad = get_post_meta( $id, 'als_undergrad', true );

		if ( ! empty( $undergrad ) ) {
			$undergrad = unserialize( $undergrad );
		}

		$undergrad[] = $value;

		$new_undergrad = serialize( $undergrad );

		update_post_meta( $id, 'als_undergrad', $new_undergrad );

	}

	private function convert_graduate( $id, $value ) {

		$graduate = get_post_meta( $id, 'als_graduate', true );

		if ( ! empty( $graduate ) ) {
			$graduate = unserialize( $graduate );
		}

		$graduate[] = $value;

		$new_graduate = serialize( $graduate );

		update_post_meta( $id, 'als_graduate', $new_graduate );

	}

	private function convert_specialty( $id, $value ) {

		update_post_meta( $id, 'als_specialty', $value );

	}

	private function convert_description( $id, $value ) {

		update_post_meta( $id, 'als_description', $value );

	}

	private function convert_award( $id, $value ) {

		$award = get_post_meta( $id, 'als_award', true );

		if ( ! empty( $award ) ) {
			$award = unserialize( $award );
		}

		$award[] = $value;

		$new_award = serialize( $award );

		update_post_meta( $id, 'als_award', $new_award );

	}

	private function convert_course( $id, $value ) {

		$course = get_post_meta( $id, 'als_course', true );

		if ( ! empty( $course ) ) {
			$course = unserialize( $course );
		}

		$course[] = $value;

		$new_course = serialize( $course );

		update_post_meta( $id, 'als_course', $new_course );

	}
	private function set_staff_options() {

		update_option( $this->option_name, $this->defaults );
		$this->options = $this->defaults;

	}
}
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

		if ( $this->check_new_install() === false ) {
			$this->update_staff_meta();
		}

		$this->set_staff_options();

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
			'posts_per_page' => -1,
		);

		$staff = get_posts( $args );

		return $staff;

	}

	private function migrate( $s ) {

		$old_meta = get_post_meta( $s->ID, $this->meta_name_old );

		if ( ! empty( $old_meta[0] ) ) {
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

		$undergrad = get_post_meta( $id, 'als_undergrad' );

		if ( empty( $value ) )
			return;

		if ( ! empty( $undergrad ) ) {

			delete_post_meta( $id, 'als_undergrad' );
			$new_undergrad = array();

			if ( is_string( $undergrad ) && $value !== $undergrad ) {
				array_push( $new_undergrad, $undergrad );
				array_push( $new_undergrad, $value );
			} elseif ( is_array( $undergrad ) &&  ! in_array( $value, $undergrad ) ) {
				$new_undergrad = $undergrad;
				array_push( $new_undergrad, $value );
			}

			update_post_meta( $id, 'als_undergrad', $new_undergrad );
			return;
		} else {
			update_post_meta( $id, 'als_undergrad', $value );
		}

	}

	private function convert_graduate( $id, $value ) {

		$graduate = get_post_meta( $id, 'als_graduate' );

		if ( empty( $value ) )
			return;

		if ( ! empty( $graduate ) ) {

			delete_post_meta( $id, 'als_graduate' );
			$new_graduate = array();

			if ( is_string( $graduate ) && $value !== $graduate ) {
				array_push( $new_graduate, $graduate );
				array_push( $new_graduate, $value );
			} elseif ( is_array( $graduate ) &&  ! in_array( $value, $graduate ) ) {
				$new_graduate = $graduate;
				array_push( $new_graduate, $value );
			}

			update_post_meta( $id, 'als_graduate', $new_graduate );
			return;
		} else {
			update_post_meta( $id, 'als_graduate', $value );
		}

	}

	private function convert_specialty( $id, $value ) {

		update_post_meta( $id, 'als_specialty', $value );

	}

	private function convert_description( $id, $value ) {

		update_post_meta( $id, 'als_description', $value );

	}

	private function convert_award( $id, $value ) {

		$award = get_post_meta( $id, 'als_award' );

		if ( empty( $value ) )
			return;

		if ( ! empty( $award ) ) {

			delete_post_meta( $id, 'als_award' );
			$new_award = array();

			if ( is_string( $award ) && $value !== $award ) {
				array_push( $new_award, $award );
				array_push( $new_award, $value );
			} elseif ( is_array( $award ) &&  ! in_array( $value, $award ) ) {
				$new_award = $award;
				array_push( $new_award, $value );
			}

			update_post_meta( $id, 'als_award', $new_award );
			return;
		} else {
			update_post_meta( $id, 'als_award', $value );
		}

	}

	private function convert_course( $id, $value ) {

		$course = get_post_meta( $id, 'als_course' );

		if ( empty( $value ) )
			return;

		if ( ! empty( $course ) ) {

			delete_post_meta( $id, 'als_course' );
			$new_course = array();

			if ( is_string( $course ) && $value !== $course ) {
				array_push( $new_course, $course );
				array_push( $new_course, $value );
			} elseif ( is_array( $course ) &&  ! in_array( $value, $course ) ) {
				$new_course = $course;
				array_push( $new_course, $value );
			}

			update_post_meta( $id, 'als_course', $new_course );
			return;
		} else {
			update_post_meta( $id, 'als_course', $value );
		}

	}
	
	private function set_staff_options() {

		update_option( $this->option_name, $this->defaults );
		$this->options = $this->defaults;

	}
}
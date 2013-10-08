<?php

class ALP_Metabox {

	public function __construct() {


	}

	/**
	 * Registers the metaboxes
	 */
	public function register_metabox() {

		$meta_boxes = $this->make_metaboxes();

		foreach ( $meta_boxes as $meta_box ) {
			new RW_Meta_Box( $meta_box );
		}

	}

	/**
	 * Checks to see if the Meta Box plugin is installed
	 */
	private function meta_box_check() {

		if ( ! class_exists( 'RW_Meta_Box' ) ) {
			ALS_Error::no_metabox_plugin();
		}

	}

	/**
	 * Builds the metabox array
	 * @return array The metaboxes
	 */
	private function make_metaboxes() {

		$prefix = STAFF_META_PREFIX;
		$meta_boxes = array();

		$meta_boxes[] = array(
			'id' => 'employee_details',
			'title' => 'Employee Information',
			'pages' => array( 'staff'),
			'context' => 'normal',
			'priority' => 'high',
			'fields' => array(
				array(
					'name' => 'First Name',
					'id' => $prefix . 'first-name',
					'type' => 'text',
				),
				array(
					'name' => 'Last Name',
					'id' => $prefix . 'last-name',
					'type' => 'text',
				),
				array(
					'name' => 'Position',
					'id' => $prefix . 'position',
					'type' => 'text',
				),
				array(
					'name' => 'Building/Room',
					'id' => $prefix . 'building-room',
					'type' => 'text',
				),
				array(
					'name' => 'Website',
					'desc' => 'Include "http://"',
					'id' => $prefix . 'website',
					'type' => 'text',
				),
				array(
					'name' => 'Phone',
					'desc' => 'ex. 777-777-7777',
					'id' => $prefix . 'phone',
					'type' => 'text',
				),
				array(
					'name' => 'Email',
					'id' => $prefix . 'email',
					'type' => 'text',
				),
			),
		);
		
		$meta_boxes[] = array(
			'id' => 'education',
			'title' => 'Education',
			'context' => 'normal',
			'priority' => 'high',
			'pages' => array( 'staff' ),
			'fields' => array(
				array(
					'name' => 'Undergraduate Degree',
					'id' => $prefix . 'undergrad',
					'type' => 'text',
					'clone' => true,
				),
				array(
					'name' => 'Graduate Degree',
					'id' => $prefix . 'graduate',
					'type' => 'text',
					'clone' => true,
				),
			),
		);

		$meta_boxes[] = array(
			'id'       => 'specialty',
			'title'    => 'Specialty',
			'context'  => 'normal',
			'priority' => 'high',
			'pages'    => array( 'staff' ),
			'fields'   => array(
				array(
					'name' => 'Section Label',
					'id' => $prefix . 'specialty-label',
					'type' => 'text',
				),
				array(
					'name' => 'Specialty',
					'id' => $prefix . 'specialty',
					'type' => 'text',
				),
				array(
					'name' => 'Description',
					'id'   => $prefix . 'description',
					'type' => 'textarea',
				),
			),
		);

		$meta_boxes[] = array(
			'id' => 'awards',
			'title' => 'Awards Received',
			'context' => 'normal',
			'priority' => 'high',
			'pages' => array( 'staff' ),
			'fields' => array(
				array(
					'name' => 'Award',
					'id' => $prefix . 'award',
					'type' => 'text',
					'clone' => true,
				),
			),
		);

		$meta_boxes[] = array(
			'id' => 'courses',
			'title' => 'Courses Taught',
			'context' => 'normal',
			'priority' => 'high',
			'pages' => array( 'staff' ),
			'fields' => array(
				array(
					'name' => 'Course taught',
					'id' => $prefix . 'course',
					'type' => 'text',
					'clone' => true,
				),
			),
		);
		

		return $meta_boxes;

	}

}
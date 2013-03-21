<?php

class ALS_Assets {

	private static $instance;

	public function __construct() {

		self::$instance = $this;

		add_action( 'wp_enqueue_scripts', array( $this, 'register_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );

	}

	public function register_styles() {

		wp_register_style(
			'staff_stylesheet',
			STAFF_PLUGIN_DIR_URL . '/css/style.css',
			array(),
			'',
			'all'
		);

	}

	public function enqueue_styles() {

		wp_enqueue_style( 'staff_stylesheet' );

	}

}
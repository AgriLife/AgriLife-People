<?php

/**
 * Loads the proper assets
 */

class ALP_Assets {

	public function __construct() {

		add_action( 'wp_enqueue_scripts', array( $this, 'register_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );

    $this->add_image_sizes();

	}

	/**
	 * Registers the styles for enqueuing
	 * @return void
	 */
	public function register_styles() {

		wp_register_style(
			'people_stylesheet',
			PEOPLE_PLUGIN_DIR_URL . '/css/style.css',
			array(),
			'',
			'all'
		);

	}

	/**
	 * Enqueues the previously registered styles
	 * @return void
	 */
	public function enqueue_styles() {

		wp_enqueue_style( 'people_stylesheet' );

	}

  /**
   * Add the required image sizes
   * @return void
   */
  public function add_image_sizes() {

    add_image_size( 'people_single', 240, 320, true );
    add_image_size( 'people_archive', 70, 105, true );

  }

}
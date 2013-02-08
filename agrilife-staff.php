<?php
/*
 * Plugin Name: AgriLife Staff
 * Plugin URI: https://github.com/channeleaton/AgriLife-Staff
 * Description: Creates a staff custom post type
 * Version: 2.0
 * Author: J. Aaron Eaton
 * Author URI: http://channeleaton.com
 * License: GPL2
 */

define( 'PLUGIN_NAME', 'AgriLife Staff' );
define( 'PLUGIN_DIRNAME', 'agrilife-staff' );

// Autoload all classes
spl_autoload_register( 'AgriLife_Staff::autoload' );

class AgriLife_Staff {

  private static $instance;

  private static $version = '2.0';

  private static $file = __FILE__;

  public function __construct() {

    self::$instance = $this;

    // Run an activation hook to make sure we are using the correct WP version
    register_activation_hook( self::$file, array( $this, 'activation_check' ) );

    // Run the upgrade script
    register_activation_hook( self::$file, array( $this, 'upgrade' ) );

    // Load up the plugin
    add_action( 'init', array( $this, 'init' ) ); 

    // Add image sizes

  }

  public function activation_check() {

  }

  public function upgrade() {

  }

  public function init() {


    
  }

 /**
   * Autoloads the requested class. PSR-0 compliant
   *
   * @since 2.0
   * @param  string $classname The name of the class
   */
  public static function autoload( $classname ) {

    $filename = dirname( __FILE__ ) .
      DIRECTORY_SEPARATOR .
      str_replace( '_', DIRECTORY_SEPARATOR, $classname ) .
      '.php';
    if ( file_exists( $filename ) )
      require $filename;

  } 

  public static function get_instance() {

    return self::$instance;

  }

}
new AgriLife_Staff;
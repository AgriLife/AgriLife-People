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

// define( 'PLUGIN_NAME:w
// ', 'AgriLife Staff' );
define( 'PLUGIN_DIRNAME', 'agrilife-staff' );
define( 'STAFF_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'META_PREFIX', 'als_' );

// Autoload all classes
spl_autoload_register( 'AgriLife_Staff::autoload' );

class AgriLife_Staff {

  private static $instance;

  public $version = '2.0';

  private static $file = __FILE__;

  public function __construct() {

    self::$instance = $this;

    // Run an activation hook to make sure we are using the correct WP version
    register_activation_hook( self::$file, array( $this, 'activation_check' ) );

    // Make sure the Meta Box plugin is installed
    add_action( 'init', array( $this, 'meta_box_check' ) );

    // Run the upgrade script
    register_activation_hook( self::$file, array( $this, 'upgrade' ) );

    // Load up the plugin
    add_action( 'init', array( $this, 'init' ) ); 

    // Add image sizes

  }

  public function activation_check() {

  }

  public function meta_box_check() {

    // Make sure that the Meta Box plugin is installed and activated
    if ( ! function_exists( 'rwmb_meta' ) ) {
      echo '<div class="updated">';
      __(
        printf( '<p>You must install/activate the <a href="%s">Meta Box</a> plugin to use the Staff custom post type.</p>',
          'http://wordpress.org/extend/plugins/meta-box/' ),
        'agrilife'
      );
      echo '</div>';
    }

  }

  public function upgrade() {
    $als_upgrade = new ALS_Upgrade;
  }

  public function init() {

    // Create the custom post type
    $als_posttype = new ALS_PostType;

    // Create the Type taxonomy
    $als_taxonomy = new ALS_Taxonomy;

    // Create the Metaboxes
    $als_metabox = new ALS_Metabox;

    // Direct to the proper templates
    $als_templates = new ALS_Templates;

    add_filter( 'title_save_pre', array( $this, 'save_staff_title' ) );

  }

  public function save_staff_title( $staff_title ) {

    $first = $_POST[META_PREFIX . 'first-name'];
    $last = $_POST[META_PREFIX . 'last-name'];
    if ( $_POST['post_type'] == 'staff' )
      $staff_title = $last . ', ' . $first;

    return $staff_title;
    
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
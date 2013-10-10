<?php
/*
 * Plugin Name: AgriLife People
 * Plugin URI: http://github.com/AgriLife/AgriLife-People 
 * Description: Creates a people custom post type.
 * Version: 0.9.1
 * Author: J. Aaron Eaton
 * Author URI: http://channeleaton.com
 * License: GPL2
 */

define( 'PEOPLE_PLUGIN_DIRNAME', 'agrilife-people' );
define( 'PEOPLE_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'PEOPLE_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );

// Autoload all classes
spl_autoload_register( 'AgriLife_People::autoload' );

class AgriLife_People {

  private static $instance;

  public $plugin_version = '1.0';

  private $option_name = 'agrilife_people';

  private $options = array();

  private $schema_version = 1;

  private static $file = __FILE__;

  /**
   * Start the engine!
   */
  public function __construct() {

    self::$instance = $this;

    // Load up the plugin
    add_action( 'init', array( $this, 'init' ) ); 

    // Add/update options on admin load
    add_action( 'admin_init', array( $this, 'admin_init' ) );

    // Setup the icons
    add_action( 'admin_head', array( $this, 'admin_head' ) );

    // Get the widgets ready
    add_action( 'widgets_init', array( $this, 'register_widgets' ) );

  }

  /**
   * Initialize the required classes
   */
  public function init() {

    // Load the plugin assets
    $alp_assets = new ALP_Assets;

    // Create the custom post type
    $alp_posttype = new ALP_PostType;

    // Create the Type taxonomy
    $alp_taxonomy = new ALP_Taxonomy;

    // Create the Metaboxes
    $alp_metabox = new ALP_Metabox;

    // Make the shortcode
    $alp_shortcode = new ALP_Shortcode;

    // Direct to the proper templates
    $alp_templates = new ALP_Templates;

    add_filter( 'title_save_pre', array( $this, 'save_people_title' ) );

    $this->add_image_sizes();

  }

  /**
   * Initialize things for the admin area
   */
  public function admin_init() {

    // Setup/update options
    if ( false === $this->options || ! isset( $this->options['schema_version'] ) || $this->options['schema_version'] < $this->schema_version ) {
                         
      //init options array
      if ( ! is_array( $this->options ) )
              $this->options = array();
             
      //establish schema version
      $current_schema_version = isset( $this->options['schema_version'] ) ? $this->options['schema_version'] : 0;
     
      //run upgrade and store new version #
      // $this->upgrade( $current_schema_version );
      $this->options['schema_version'] = $this->schema_version;
      update_option( $this->option_name, $this->options );
           
    }

  }

  /**
   * Set up the admin menu icon
   * @return void
   */
  public function admin_head() { ?>

  <style type="text/css" media="screen">
    #menu-posts-people .wp-menu-image {
      background: url('<?php echo PEOPLE_PLUGIN_DIR_URL; ?>/img/user.png') no-repeat 6px -17px !important;
    }
    #menu-posts-people:hover .wp-menu-image, #menu-posts-people.wp-has-current-submenu .wp-menu-image {
      background-position:6px 7px!important;
    }
  </style>

  <?php }

  /**
   * Register widgets
   */
  public function register_widgets() {

    register_widget( 'ALP_Widget_FeaturedPerson' );

  }

  /**
   * Saves the person title as lastname, firstname
   * @param  string $people_title The empty staff title
   * @return string              The correct staff title
   */
  public function save_people_title( $people_title ) {

    if ( ! empty( $_POST['post_type'] ) && $_POST['post_type'] == 'people' ){
      global $post;

      $first = $_POST['fields']['field_52540a7be7804'];
      $last = $_POST['fields']['field_52540aa741c37'];

      $people_title = sprintf( '%2$s, %1$s', $first, $last );
    }

    return $people_title;
    
  }

  /**
   * Add the required image sizes
   */
  public function add_image_sizes() {

    add_image_size( 'people_single', 240, 240, true );
    add_image_size( 'people_archive', 70, 70, true );

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

new AgriLife_People;
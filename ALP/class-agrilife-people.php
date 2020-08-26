<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/AgriLife/agrilife-people/blob/master/ALP/class-agrilifepeople.php
 * @since      1.5.7
 * @package    agrilife-people
 * @subpackage agrilife-people/ALP
 */

/**
 * The core plugin class
 *
 * @since 0.1.0
 * @return void
 */
class AgriLife_People {

	/**
	 * Instance of this class
	 *
	 * @var object
	 */
	private static $instance;

	/**
	 * The current plugin version
	 *
	 * @var string
	 */
	public $plugin_version = '1.0';

	/**
	 * The name of the plugin option
	 *
	 * @var string
	 */
	private $option_name = 'agrilife_people';

	/**
	 * The array of plugin options
	 *
	 * @var array
	 */
	private $options = array();

	/**
	 * The current meta schema version
	 *
	 * @var integer
	 */
	private $schema_version = 1;

	/**
	 * The current file
	 *
	 * @var string
	 */
	private static $file = __FILE__;

	/**
	 * Start the engine!
	 */
	public function __construct() {

		self::$instance = $this;

		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

		// Load up the plugin.
		add_action( 'init', array( $this, 'init' ) );

		// Add/update options on admin load.
		add_action( 'admin_init', array( $this, 'admin_init' ) );

		// Display admin notifications.
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );

		// Get the widgets ready.
		add_action( 'widgets_init', array( $this, 'register_widgets' ) );

		// Ensure the Relevanssi plugin doesn't hide People posts.
		add_filter( 'relevanssi_prevent_default_request', array( $this, 'rlv_fix_archive_kill' ), 10, 2 );

		add_action( 'acf/init', array( $this, 'options_page' ), 9 );
		add_action( 'acf/init', array( $this, 'genesis_layout_options' ), 11 );

		// Sort posts by last name field.
		add_action( 'pre_get_posts', array( $this, 'alp_pre_get_posts' ) );

	}

	/**
	 * Ensure the Relevanssi plugin doesn't hide People posts
	 *
	 * @param boolean $kill Whether or not to prevent the default request.
	 * @param object  $query The query object.
	 * @return boolean
	 */
	public function rlv_fix_archive_kill( $kill, $query ) {
		if ( empty( $query->query_vars[ ‘s’ ] ) ) {
			$kill = false;
		}
		return $kill;
	}

	/**
	 * Items to run on plugin activation
	 *
	 * @return void
	 */
	public function activate() {

		// Flush rewrite rules as we're creating new post types and taxonomies.
		flush_rewrite_rules();

	}

	/**
	 * Items to run on plugin deactivation
	 *
	 * @return void
	 */
	public function deactivate() {

		flush_rewrite_rules();

	}

	/**
	 * Initialize the required classes
	 *
	 * @return void
	 */
	public function init() {

		// Load the plugin assets.
		require_once PEOPLE_PLUGIN_DIR_PATH . '/ALP/Assets.php';
		$alp_assets = new ALP_Assets();

		// Load the plugin assets.
		require_once PEOPLE_PLUGIN_DIR_PATH . '/ALP/class-alp-message.php';
		$alp_assets = new ALP_Message();

		// Create the custom post type.
		require_once PEOPLE_PLUGIN_DIR_PATH . '/ALP/class-posttype.php';
		$alp_posttype = new PostType();

		// Create the Type taxonomy.
		require_once PEOPLE_PLUGIN_DIR_PATH . '/ALP/Taxonomy.php';
		$alp_taxonomy = new ALP_Taxonomy();

		// Create the Metaboxes.
		require_once PEOPLE_PLUGIN_DIR_PATH . '/ALP/class-alp-metabox.php';
		$alp_metabox = new ALP_Metabox();

		// Make the shortcode.
		require_once PEOPLE_PLUGIN_DIR_PATH . '/ALP/Shortcode.php';
		$alp_shortcode = new ALP_Shortcode( PEOPLE_PLUGIN_DIR_PATH );

		// Direct to the proper templates.
		require_once PEOPLE_PLUGIN_DIR_PATH . '/ALP/Templates.php';
		$alp_templates = new ALP_Templates();

	}

	/**
	 * Initialize things for the admin area
	 *
	 * @return void
	 */
	public function admin_init() {

		// Setup/update options.
		if ( false === $this->options || ! isset( $this->options['schema_version'] ) || $this->options['schema_version'] < $this->schema_version ) {

			// init options array.
			if ( ! is_array( $this->options ) ) {
				$this->options = array();
			}

			// establish schema version.
			$current_schema_version = isset( $this->options['schema_version'] ) ? $this->options['schema_version'] : 0;

			$this->options['schema_version'] = $this->schema_version;
			update_option( $this->option_name, $this->options );

		}

	}

	/**
	 * Displays admin notices
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function admin_notices() {

		if ( ! class_exists( 'Acf' ) ) {
			ALP_Message::install_plugin( 'Advanced Custom Fields' );
		}

	}

	/**
	 * Register widgets
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function register_widgets() {

		register_widget( 'ALP_Widget_FeaturedPerson' );

	}

	/**
	 * Registers the plugin settings page.
	 *
	 * @since 1.6.0
	 * @return void
	 */
	public function options_page() {

		if ( function_exists( 'acf_add_options_page' ) ) {

			$settings = array(
				'page_title'  => __( 'Settings' ),
				'menu_title'  => __( 'Settings' ),
				'menu_slug'   => 'agrilife-people-settings',
				'capability'  => 'edit_plugins',
				'position'    => '',
				'parent_slug' => 'edit.php?post_type=people',
			);

			acf_add_options_page( $settings );

			if ( function_exists( 'acf_add_local_field_group' ) ) {

				acf_add_local_field_group(
					array(
						'key'                   => 'group_5e14d2d88b327',
						'title'                 => 'AgriLife People Settings',
						'fields'                => array(),
						'location'              => array(
							array(
								array(
									'param'    => 'options_page',
									'operator' => '==',
									'value'    => 'agrilife-people-settings',
								),
							),
						),
						'menu_order'            => 0,
						'position'              => 'normal',
						'style'                 => 'default',
						'label_placement'       => 'top',
						'instruction_placement' => 'label',
						'hide_on_screen'        => '',
						'active'                => true,
						'description'           => '',
					)
				);

			}
		}
	}

	/**
	 * Registers the plugin settings page fields.
	 *
	 * @since 1.6.0
	 * @return void
	 */
	public function genesis_layout_options() {

		if ( function_exists( 'acf_add_local_field' ) ) {

			$enabled_layouts = genesis_get_layouts( 'site' );
			$choices         = array( 'default' => 'Site Default' );
			foreach ( $enabled_layouts as $key => $value ) {
				$choices[ $key ] = $value['label'];
			}

			acf_add_local_field(
				array(
					'key'               => 'field_5f441f3fb9695',
					'label'             => 'Single Person Layout',
					'name'              => 'single_person_layout',
					'type'              => 'select',
					'instructions'      => '',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'choices'           => $choices,
					'default_value'     => 'default',
					'allow_null'        => 0,
					'multiple'          => 0,
					'ui'                => 0,
					'return_format'     => 'value',
					'ajax'              => 0,
					'placeholder'       => '',
					'parent'            => 'group_5e14d2d88b327',
				)
			);
			acf_add_local_field(
				array(
					'key'               => 'field_5f441f3fb9696',
					'label'             => 'Search and Archive Layout',
					'name'              => 'person_list_layout',
					'type'              => 'select',
					'instructions'      => '',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'choices'           => $choices,
					'default_value'     => 'default',
					'allow_null'        => 0,
					'multiple'          => 0,
					'ui'                => 0,
					'return_format'     => 'value',
					'ajax'              => 0,
					'placeholder'       => '',
					'parent'            => 'group_5e14d2d88b327',
				)
			);
		}
	}

	/**
	 * Order people posts by last name custom field.
	 *
	 * @since 1.6.0
	 * @param object $query The current post query.
	 *
	 * @return object
	 */
	public function alp_pre_get_posts( $query ) {

		// Do not modify queries in the admin.
		if ( is_admin() ) {

			return $query;

		}

		if ( isset( $query->query_vars['post_type'] ) && 'people' === $query->query_vars['post_type'] ) {

			$query->set( 'orderby', 'meta_value' );
			$query->set( 'meta_key', 'ag-people-last-name' );
			$query->set( 'order', 'ASC' );

		}

		return $query;

	}

	/**
	 * Autoloads the requested class. PSR-0 compliant
	 *
	 * @since 0.1.0
	 * @param string $classname The name of the class.
	 */
	public static function autoload( $classname ) {

		$filename = dirname( __FILE__ ) .
		DIRECTORY_SEPARATOR .
		str_replace( '_', DIRECTORY_SEPARATOR, $classname ) .
		'.php';
		if ( file_exists( $filename ) ) {
			require $filename;
		}

	}

	/**
	 * Return instance of class
	 *
	 * @since 0.1.0
	 * @return object.
	 */
	public static function get_instance() {

		return null === self::$instance ? new self() : self::$instance;

	}

}

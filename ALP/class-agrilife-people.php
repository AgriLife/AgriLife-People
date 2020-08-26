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

		// Change Genesis page layout based on settings.
		add_filter( 'genesis_pre_get_option_site_layout', array( $this, 'alp_single_person_layout_filter' ) );

		// Format People excerpt output.
		add_filter( 'get_the_excerpt', array( $this, 'filter_site_search_results' ) );

		// Set the post title as Last, First.
		add_action( 'save_post', array( $this, 'save_people_title' ) );

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
		add_action( 'init', array( $this, 'remove_post_type_support' ), 12 );

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
	 * Remove post type support.
	 *
	 * @since 1.6.0
	 * @return void
	 */
	public function remove_post_type_support() {

		remove_post_type_support( 'people', 'genesis-entry-meta-before-content' );

	}

	/**
	 * Set person layout choice.
	 *
	 * @since 1.6.0
	 * @param mixed $layout Return string of layout choice or false to use site default.
	 * @return mixed
	 */
	public function alp_single_person_layout_filter( $layout ) {

		$field_name = false;
		if ( is_archive() && is_post_type_archive( 'people' ) ) {
			$field_name = 'person_list_layout';
		} elseif ( is_singular( 'people' ) ) {
			$field_name = 'single_person_layout';
		}

		if ( $field_name ) {

			$layout_choice = get_field( $field_name, 'option' );

			if ( ! empty( $layout_choice ) && 'default' !== $layout_choice ) {

				return $layout_choice;

			}
		}

		return $layout;

	}

	/**
	 * Format search result information
	 *
	 * @since 1.6.0
	 * @param string $excerpt The post excerpt.
	 * @return string
	 */
	public function filter_site_search_results( $excerpt ) {

		$type = get_post_type();
		if ( 'people' === $type ) {
			// People post types don't have an excerpt, so it's built here.
			// Based on single-people.php from AgriLife People plugin.
			$parts = array();

			$title = get_field( 'ag-people-title' );
			if ( $title ) {
				$parts[] = 'Title: ' . $title;
			}

			$office = get_field( 'ag-people-office-location' );
			if ( $office ) {
				$parts[] = 'Office: ' . $office;
			}

			$email = get_field( 'ag-people-email' );
			if ( $email ) {
				$parts[] = 'Email: ' . $email;
			}

			$phone = get_field( 'ag-people-phone' );
			if ( $phone ) {
				$parts[] = 'Phone: ' . $phone;
			}

			$resume = get_field( 'ag-people-resume' );
			if ( $resume ) {
				$parts[] = 'Resume/CV: ' . $resume;
			}

			$website = get_field( 'ag-people-website' );
			if ( $website ) {
				$parts[] = $website;
			}

			$undergrad_items = array();
			if ( get_field( 'ag-people-undergrad' ) ) {
				while ( has_sub_field( 'ag-people-undergrad' ) ) :
					$undergrad_items[] = get_sub_field( 'ag-people-undergrad-degree' );
					endwhile;
			}
			$undergrad = implode( ',', $undergrad_items );
			if ( ! empty( $undergrad ) ) {
				$parts[] = 'Undergraduate Education: ' . $undergrad;
			}

			$grad_items = array();
			if ( get_field( 'ag-people-graduate' ) ) {
				while ( has_sub_field( 'ag-people-graduate' ) ) :
					$grad_items[] = get_sub_field( 'ag-people-graduate-degree' );
					endwhile;
			}
			$grad = implode( ',', $grad_items );
			if ( ! empty( $grad ) ) {
				$parts[] = 'Graduate Education: ' . $grad;
			}

			$award_items = array();
			if ( get_field( 'ag-people-awards' ) ) {
				while ( has_sub_field( 'ag-people-awards' ) ) :
					$award_items[] = get_sub_field( 'ag-people-award' );
					endwhile;
			}
			$award = implode( ', ', $award_items );
			if ( ! empty( $award ) ) {
				$parts[] = 'Awards: ' . $award;
			}

			$taught_items = array();
			if ( get_field( 'ag-people-courses' ) ) {
				while ( has_sub_field( 'ag-people-courses' ) ) :
					$taught_items[] = get_sub_field( 'ag-people-course' );
					endwhile;
			}
			$taught = implode( ', ', $taught_items );
			if ( ! empty( $taught ) ) {
				$parts[] = 'Courses Taught: ' . $taught;
			}

			$content_items = array();
			while ( has_sub_field( 'ag-people-content' ) ) :
				$layout = get_row_layout();
				switch ( $layout ) {
					case 'ag-people-content-header':
						$content_items[] = get_sub_field( 'header' );
						break;
					case 'ag-people-content-text':
						$content_items[] = get_sub_field( 'text' );
						break;
					default:
						break;
				}
			endwhile;
			$content = implode( ', ', $content_items );
			if ( ! empty( $content ) ) {
				$parts[] = $content;
			}

			// Concatenate people data to string.
			$excerpt = implode( '; ', $parts );
			// Remove HTML.
			$excerpt = wp_strip_all_tags( $excerpt );
			// Combine all people data for excerpt.
			$excerpt = preg_replace( '/ ;/', ';', $excerpt );
			// Replace special and excess whitespace characters with normal spaces.
			$excerpt = str_replace( '&nbsp;', ' ', $excerpt );
			$excerpt = preg_replace( '/[\s\t\r\n\v]+/', ' ', $excerpt );
			$excerpt = preg_replace( '/[^\w\d,.;"\')]?\h\s/', ' ', $excerpt );
			// Restrict word count.
			$excerpt_length = apply_filters( 'excerpt_length', 50 );
			$excerpt_more   = apply_filters( 'excerpt_more', ' [&hellip;]' );
			$excerpt        = wp_trim_words( $excerpt, $excerpt_length, $excerpt_more );

		}

		return $excerpt;

	}

	/**
	 * Saves the title as Last, First
	 *
	 * @since 1.6.0
	 * @param  int $post_id The current post ID.
	 * @return void
	 */
	public function save_people_title( $post_id ) {

		$type = get_post_type( $post_id );

		if ( 'people' === $type ) {

			$first_name = get_field( 'ag-people-first-name', $post_id );
			$last_name  = get_field( 'ag-people-last-name', $post_id );

			if ( ! empty( $first_name ) || ! empty( $last_name ) ) {

				remove_action( 'save_post', array( $this, 'save_people_title' ) );

				$people_title = sprintf(
					'%s, %s',
					$last_name,
					$first_name
				);

				$people_slug = sanitize_title( $people_title );

				$args = array(
					'ID'         => $post_id,
					'post_title' => $people_title,
					'post_name'  => $people_slug,
				);

				wp_update_post( $args );

				add_action( 'save_post', array( $this, 'save_people_title' ) );

			}
		}

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

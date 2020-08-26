<?php
/**
 * Creates the shortcode to list people. Can be filtered by Type taxonomy.
 *
 * @link       https://github.com/AgriLife/agrilife-people/blob/master/ALP/class-alp-shortcode.php
 * @since      1.5.7
 * @package    agrilife-people
 * @subpackage agrilife-people/ALP
 */

/**
 * The shortcode class.
 *
 * @since 0.1.0
 * @return void
 */
class ALP_Shortcode {

	/**
	 * Slug of the shortcode.
	 *
	 * @var string
	 */
	private $name;

	/**
	 * Person template file.
	 *
	 * @var string
	 */
	private $loop_path;

	/**
	 * Plugin directory path.
	 *
	 * @var string
	 */
	private $path;

	/**
	 * Construct function.
	 *
	 * @since 0.1.0
	 * @param string $path The plugin directory path.
	 *
	 * @return void
	 */
	public function __construct( $path = '' ) {

		$this->name      = 'people_listing';
		$this->path      = $path;
		$filevar         = ! defined( 'AG_EXTRES_DIR_PATH' ) ? '' : '-research';
		$this->loop_path = $this->path . "views/people-list{$filevar}.php";

		add_shortcode( $this->name, array( $this, 'create_shortcode' ) );

	}

	/**
	 * Renders the 'people_listing' shortcode
	 *
	 * @param  string $atts The shortcode attributes.
	 * @return string       The shortcode output.
	 */
	public function create_shortcode( $atts ) {

		$defaults = apply_filters(
			"shortcode_atts_{$this->name}",
			array(
				'type'          => '',
				'search'        => 'false',
				'lastnamefirst' => false,
				'orderby'       => 'title',
				'order'         => 'ASC',
			)
		);

		extract( shortcode_atts( $defaults, $atts, $this->name ) ); //phpcs:ignore

		/*
		Sanitize shortcode attributes
		---------------------------------------------
		*/
		$type          = esc_attr( $type ) ?: $defaults['type'];
		$lastnamefirst = 'true' === $lastnamefirst ? true : false;
		$order         = 'DESC' === $order ? 'DESC' : 'ASC';
		$orderby       = sanitize_sql_orderby( $orderby ) ?: $defaults['orderby'];

		/*
		Output
		---------------------------------------------
		*/
		require_once PEOPLE_PLUGIN_DIR_PATH . '/ALP/Query.php';
		$people = ALP_Query::get_people( $type, $search, $order, $orderby );

		ob_start();
		if ( 'false' !== $search ) {
			require_once PEOPLE_PLUGIN_DIR_PATH . '/ALP/class-alp-templates.php';
			ALP_Templates::search_form();
		}

		require $this->loop_path;

		$output = ob_get_contents();
		ob_clean();

		return $output;

	}

}

<?php

/**
 * Creates the shortcode to list people. Can be filtered by Type taxonomy
 */

class ALP_Shortcode {

	private $name;

	private $loop_path;

	private $path;

	public function __construct( $path = '' ) {

		$this->name = 'people_listing';
		$this->path = $path;
		$filevar = !defined('AG_EXTRES_DIR_PATH') ? '' : '-research';
		$this->loop_path = $this->path . "views/people-list{$filevar}.php";

		add_shortcode( $this->name, array( $this, 'create_shortcode' ) );

	}

	/**
	 * Renders the 'people_listing' shortcode
	 * @param  string $atts The shortcode attributes
	 * @return string       The shortcode output
	 */
	public function create_shortcode( $atts ) {

		$defaults = apply_filters( "shortcode_atts_{$this->name}", array(
			'type'          => '',
			'search'        => 'false',
			'lastnamefirst' => false,
			'orderby'       => 'title',
			'order'         => 'ASC'
		) );

		extract( shortcode_atts( $defaults, $atts, $this->name ) );

		/* Sanitize shortcode attributes
		--------------------------------------------- */
		$type          = esc_attr( $type ) ?: $defaults['type'];
		$lastnamefirst = $lastnamefirst === 'true' ? true : false;
		$order         = $order === 'DESC' ? 'DESC' : 'ASC';
		$orderby       = sanitize_sql_orderby( $orderby ) ?: $defaults['orderby'];

		/* Output
		--------------------------------------------- */
		$people = ALP_Query::get_people( $type, $search, $orderby, $order );

		ob_start();
		if ( $search !== 'false' ) {
			ALP_Templates::search_form();
		}

		require $this->loop_path;

		$output = ob_get_contents();
		ob_clean();

		return $output;

	}

}

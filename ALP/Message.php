<?php

/**
 * Provides various messages used in the plugin
 */

class ALP_Message {

	/**
	 * Prints error when a required plugin is not installed/activated
	 * @param  string $name Name of required plugin
	 * @return void
	 */
	public static function install_plugin( $name ) {

		printf( '<div class="error"><p>AgriLife People requires an additional plugin: <strong>%s</strong>. Please install/activate this now.</p></div>',
						$name
					);

	}

}
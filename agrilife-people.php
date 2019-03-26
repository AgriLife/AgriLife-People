<?php
/**
 * AgriLife People
 *
 * @package      Agrilife
 * @author       J. Aaron Eaton, Zachary Watkins
 * @copyright    2019 Texas A&M AgriLife Communications
 * @license      GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name:  AgriLife People
 * Plugin URI:   https://github.com/AgriLife/agrilife-people
 * Description:  Creates a people custom post type.
 * Version:      1.5.11
 * Author:       J. Aaron Eaton, Zachary Watkins
 * Author Email: zachary.watkins@ag.tamu.edu
 * Text Domain:  agrilife
 * License:      GPL-2.0+
 * License URI:  http://www.gnu.org/licenses/gpl-2.0.txt
 */

define( 'PEOPLE_PLUGIN_DIRNAME', 'agrilife-people' );
define( 'PEOPLE_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'PEOPLE_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );

// Autoload all classes.
require_once PEOPLE_PLUGIN_DIR_PATH . '/ALP/class-agrilife-people.php';
spl_autoload_register( 'AgriLife_People::autoload' );
AgriLife_People::get_instance();

require_once PEOPLE_PLUGIN_DIR_PATH . '/ALP/Widget/FeaturedPerson.php';

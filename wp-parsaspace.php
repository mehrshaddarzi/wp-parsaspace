<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://realwp.net/
 * @since             1.0.4
 * @package           Wp_Parsaspace
 *
 * @wordpress-plugin
 * Plugin Name:       ParsaSpace CDN Host
 * Plugin URI:        https://realwp.net/parsaspace
 * GitHub URI:        mehrshaddarzi/wp-parsaspace
 * Description:       Connect your WordPress Media Library to the ParsSpace CDN Host and Enjoy
 * Version:           1.0.4
 * Author:            Mehrshad Darzi
 * Author URI:        https://realwp.net/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-parsaspace
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WP_PARSASPACE_VERSION', '1.0.4' );
define( 'WP_PARSASPACE_DIR_URL', plugin_dir_url(__FILE__) );
define( 'WP_PARSASPACE_DIR_PATH', plugin_dir_path(  __FILE__  ) );

/**
 * Update Automatic Plugin From Github
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-parsaspace-updater.php';


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-parsaspace-activator.php
 */
function activate_wp_parsaspace() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-parsaspace-activator.php';
	Wp_Parsaspace_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-parsaspace-deactivator.php
 */
function deactivate_wp_parsaspace() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-parsaspace-deactivator.php';
	Wp_Parsaspace_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_parsaspace' );
register_deactivation_hook( __FILE__, 'deactivate_wp_parsaspace' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-parsaspace.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_parsaspace() {

	$plugin = new Wp_Parsaspace();
	$plugin->run();

}
run_wp_parsaspace();
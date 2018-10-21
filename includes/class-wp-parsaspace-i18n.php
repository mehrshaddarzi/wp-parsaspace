<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://realwp.net/
 * @since      1.0.0
 *
 * @package    Wp_Parsaspace
 * @subpackage Wp_Parsaspace/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wp_Parsaspace
 * @subpackage Wp_Parsaspace/includes
 * @author     Mehrshad Darzi <realwp.ir@gmail.com>
 */
class Wp_Parsaspace_i18n {

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

        if ( get_locale() == 'fa_IR' ) {
            load_textdomain( 'wp-parsaspace',  WP_PARSASPACE_DIR_PATH. '/languages/wp-parsaspace-fa_IR.mo' );
        }

	}



}

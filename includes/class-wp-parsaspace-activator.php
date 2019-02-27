<?php

/**
 * Fired during plugin activation
 *
 * @link       https://realwp.net/
 * @since      1.0.0
 *
 * @package    Wp_Parsaspace
 * @subpackage Wp_Parsaspace/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_Parsaspace
 * @subpackage Wp_Parsaspace/includes
 * @author     Mehrshad Darzi <realwp.ir@gmail.com>
 */
class Wp_Parsaspace_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		/*
		 * Not Allowed Active Plugin in Localhost
		 */
		if ( in_array( $_SERVER['REMOTE_ADDR'], array( '127.0.0.1', '::1' ) ) ) {
			wp_die( __( 'این افزونه در Localhost قابلیت نصب ندارد', 'wp-parsaspace' ) . '<br><a href="' . admin_url( 'plugins.php' ) . '">' . __( 'بازگشت', 'wp-parsaspace' ) . '</a>' );
		}

		/*
		 * check curl is install in Server
		 */
		if ( ! function_exists( 'curl_version' ) ) {
			wp_die( 'جهت نصب این افزونه نیاز به فعال بودن Curl در سرور شما می باشد لطفا با پشتیبانی هاست خود در این باره تماس حاصل فرمایید<br><a href="' . admin_url( 'plugins.php' ) . '">' . __( 'بازگشت', 'wp-parsaspace' ) . '</a>' );
		}

		/*
		 * Create Option For Plugin
		 */
		$opt_name = "wp_parsaspace_opt";
		if ( get_option( $opt_name ) === false ) {
			$value = [
				'api_token'           => '', //Token Api For Parsaspace Account
				'domain_name'         => '', //Cdn Url
				'is_ssl'              => 'no', //yes or No
				'base_folder'         => 'wp',
				'install_step'        => 'yes', //Default Yes
				'is_active'           => 'no', //Check Api Test Is Ok
				'is_optimize'         => 'no', //Yes Or No
				'is_automatic_upload' => 'no',
				'quality_jpg'         => 90,
				'max_size_upload'     => 50,
				'remote_dir'          => 'direct',
			];
			add_option( $opt_name, $value, '', 'yes' );
		}

		/**
		 * Create Version option
		 */
		$opt_version = "wp_parsaspace_version";
		if ( get_option( $opt_version ) === false ) {
			add_option( $opt_version, WP_PARSASPACE_VERSION, '', 'yes' );
		}

	}
}

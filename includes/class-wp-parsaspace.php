<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://realwp.net/
 * @since      1.0.0
 *
 * @package    Wp_Parsaspace
 * @subpackage Wp_Parsaspace/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wp_Parsaspace
 * @subpackage Wp_Parsaspace/includes
 * @author     Mehrshad Darzi <realwp.ir@gmail.com>
 */
class Wp_Parsaspace {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wp_Parsaspace_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'WP_PARSASPACE_VERSION' ) ) {
			$this->version = WP_PARSASPACE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'wp-parsaspace';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_admin_ajax();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wp_Parsaspace_Loader. Orchestrates the hooks of the plugin.
	 * - Wp_Parsaspace_i18n. Defines internationalization functionality.
	 * - Wp_Parsaspace_Admin. Defines all hooks for the admin area.
	 * - Wp_Parsaspace_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-parsaspace-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-parsaspace-i18n.php';


		/**
		 * The class responsible for defining Fronted code in Admin
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-parsaspace-admin-ui.php';

		/**
		 * The class responsible for defining Ajax Process Admin
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-parsaspace-admin-ajax.php';


		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-parsaspace-admin.php';


		/**
		 * The class for defining Function Work ParsaSpace api.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-parsaspace-api.php';


		/**
		 * The class for defining Helper function Plugin
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-parsaspace-srdb.php';

		/**
		 * The class for Search and Replace srdb
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-parsaspace-helper.php';

		$this->loader = new Wp_Parsaspace_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wp_Parsaspace_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wp_Parsaspace_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}


	/**
	 * Define the Admin Ajax function in plugin
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_ajax() {
		$plugin_ajax = new Wp_Parsaspace_Admin_Ajax();

	}


	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Wp_Parsaspace_Admin( $this->get_plugin_name(), $this->get_version() );

		/*
		 * Check Version Update
		 */
		$this->loader->add_action( 'admin_init', $plugin_admin, 'update_version' );

		/*
		 * Admin init Action
		 */
		$this->loader->add_action( 'admin_init', $plugin_admin, 'admin_init' );

		/*
		 * Admin Notice Wordpress
		 */
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'admin_notices' );

		/*
		 * Admin Footer Action
		 */
		$this->loader->add_action( 'admin_footer', $plugin_admin, 'admin_footer' );

		/*
		 * Add Style Admin Page
		 */
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );

		/*
		 * Add Script Admin Page
		 */
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		/*
		 * Add Admin Menu
		 */
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'admin_menu' );


		/*
		 * Add Script For Plugin Deactive Page
		 */
		$this->loader->add_action( 'admin_footer-plugins.php', $plugin_admin, 'admin_print_scripts_plugin_deactive' );


		/*
		 * Check is Active Connect Parsaspace
		 */
		$opt = get_option( 'wp_parsaspace_opt' );
		if ( $opt['is_active'] == "yes" ) {

			/*
			 * Action Change Url Attachment
			 */
			$this->loader->add_filter( 'wp_get_attachment_url', $plugin_admin, 'get_attachment_url' );
			$this->loader->add_filter( 'wp_calculate_image_srcset', $plugin_admin, 'abc_custom_image_srcset', 10, 5 );

			/*
			* Action Add field To Upload Media Table
			*/
			$this->loader->add_filter( 'manage_media_columns', $plugin_admin, 'manage_media_columns' );
			$this->loader->add_action( 'manage_media_custom_column', $plugin_admin, 'manage_media_custom_column', 10, 2 );
			$this->loader->add_action( 'admin_print_styles-upload.php', $plugin_admin, 'admin_print_styles_upload_table' );


			/*
			* Action Add Bul Action to Upload.php
			*/
			$this->loader->add_filter( 'bulk_actions-upload', $plugin_admin, 'bulk_actions_upload' );
			$this->loader->add_filter( 'handle_bulk_actions-upload', $plugin_admin, 'handle_bulk_actions_upload', 10, 3 );


			/*
			 * Jpeg Quality if not Plugin Compress Image
			 */
			if ( $opt['is_optimize'] == "no" ) {
				remove_all_filters( 'jpeg_quality' );
				$this->loader->add_filter( 'jpeg_quality', $plugin_admin, 'jpeg_quality' );
			}

			/*
			 * Change Max Upload size in Wordpress
			 */
			remove_all_filters( 'upload_size_limit' );
			$this->loader->add_filter( 'upload_size_limit', $plugin_admin, 'upload_size_limit', 20 );

			//if ( $opt['is_automatic_upload'] == "yes" ) {

				/*
				 * Action Upload File Non Image Type in Wordpress
				 */
				//$this->loader->add_action( 'add_attachment', $plugin_admin, 'add_file_non_image_to_parsaspace' );


				/*
				 * Action Upload Image File in Wordpress
				 */
				//$this->loader->add_filter( 'wp_generate_attachment_metadata', $plugin_admin, 'add_file_image_to_parsaspace' );

			//}

			/*
			 * Action Remove File From ParsSpace
			 */
			$this->loader->add_action( 'delete_attachment', $plugin_admin, 'wp_remove_file' );


			/*
			 * Cron for Optimize Image in Wordpress
			 */
			$this->loader->add_action( 'cron_upload_image', $plugin_admin, 'cron_upload_image', 10, 1 );
			$this->loader->add_action( 'cron_upload_file', $plugin_admin, 'cron_upload_file', 10, 1 );


			/*
			 * Cron Delete File
			 */
			$this->loader->add_action( 'cron_remove_attachment', $plugin_admin, 'cron_remove_attachment', 10, 1 );

		}

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Wp_Parsaspace_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}

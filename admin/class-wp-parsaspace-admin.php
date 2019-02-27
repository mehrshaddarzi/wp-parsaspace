<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://realwp.net/
 * @since      1.0.0
 *
 * @package    Wp_Parsaspace
 * @subpackage Wp_Parsaspace/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Parsaspace
 * @subpackage Wp_Parsaspace/admin
 * @author     Mehrshad Darzi <realwp.ir@gmail.com>
 */
class Wp_Parsaspace_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name The name of this plugin.
	 * @param      string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		$screen = get_current_screen();

		/*
		* Load Only Plugin Page
		*/
		if ( $screen->id == 'toplevel_page_parsaspace_setting' ) {
			wp_enqueue_style( 'Font-Awesome', plugin_dir_url( __FILE__ ) . 'css/font-awesome/font-awesome.min.css', array(), $this->version, 'all' );
		}

		wp_enqueue_style( 'jQuery-Confrim-Style', plugin_dir_url( __FILE__ ) . 'js/jquery-confirm/jquery-confirm.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-parsaspace-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		$screen = get_current_screen();

		/*
		 * Load Only Plugin Page
		 */
		if ( $screen->id == 'toplevel_page_parsaspace_setting' ) {
			wp_enqueue_script( 'jQuery-chart-js', plugin_dir_url( __FILE__ ) . 'js/chart-js/chart.min.js', array( 'jquery' ), $this->version, false );

			/*
			 * Remove Show Update wordpress This Page
			 */
			remove_action( 'admin_notices', 'update_nag', 3 );
			remove_action( 'admin_notices', 'maintenance_nag', 10 );
		}

		wp_enqueue_script( 'jQuery-Confrim-Js', plugin_dir_url( __FILE__ ) . 'js/jquery-confirm/jquery-confirm.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-parsaspace-admin.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'wp_parsaspace_ajax', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'security' => wp_create_nonce( 'wp_parsaspace_token' )
		) );

	}


	/**
	 * Register the Admin Menu
	 *
	 * @since    1.0.0
	 */
	public function admin_menu() {
		$this->pagehook = add_menu_page( __( 'ParsaSpace', 'wp-parsaspace' ), __( 'ParsaSpace', 'wp-parsaspace' ), 'manage_options', 'parsaspace_setting', array( 'Wp_Parsaspace_Admin_Ui', 'setting_page' ), 'dashicons-cloud', 10 );

		//register callback gets call prior your own page gets rendered
		add_action( 'load-' . $this->pagehook, array( $this, 'on_load_page_meta_box_init' ) );
	}


	/**
	 * init Meta Box in Admin Page Area
	 *
	 * @since    1.0.0
	 */
	public function on_load_page_meta_box_init() {
		//ensure, that the needed javascripts been loaded to allow drag/drop, expand/collapse and hide/show of boxes
		foreach ( array( 'common', 'wp-lists', 'postbox' ) as $src ) {
			wp_enqueue_script( $src );
		}

		//add several metaboxes now, all metaboxes registered during load page can be switched off/on at "Screen Options" automatically, nothing special to do therefore
		$meta_box_list = array(
			'static_media'       => array( 'fa-area-chart', 'آمار فایل های وب سایت', 'side' ),
			'static_media_cluod' => array( 'fa-cloud', 'آمار میزبانی فایل ها', 'side' ),
			'developer'          => array( 'fa-code', 'درباره توسعه دهنده', 'side' ),
			'review'             => array( 'fa-desktop', 'وضعیت سیستم', 'normal' ),
			'remote_upload'      => array( 'fa-download', ' دانلود فایل از سایت های دیگر و انتقال به حساب پارسا اسپیس', 'normal' ),
			'system'             => array( 'fa-cog', 'تنظیمات افزونه', 'normal' ),
		);
		foreach ( $meta_box_list as $meta_id => $meta_inf ) {
			add_meta_box( 'wp_parsaspace_meta_box_' . $meta_id, '<i class="fa ' . $meta_inf[0] . '"></i> ' . $meta_inf[1], array( 'Wp_Parsaspace_Admin_Ui', 'meta_box_' . $meta_id ), $this->pagehook, $meta_inf[2], 'core' );
		}

	}

	/**
	 * Check is Localhost Test
	 *
	 * @since    1.0.0
	 */
	public function is_localhost( $whitelist = array( '127.0.0.1', '::1' ) ) {
		return in_array( $_SERVER['REMOTE_ADDR'], $whitelist );
	}


	/**
	 * Wp Get Attachment Url filter
	 *
	 * @since    1.0.0
	 */
	public function get_attachment_url( $url ) {
		$post_id     = attachment_url_to_postid( $url );
		$cdn         = get_post_meta( $post_id, 'cdn_parsaspace', true );
		$base_upload = wp_upload_dir();
		$uploads     = $base_upload['baseurl'];
		if ( $cdn == 1 ) {
			$url = str_replace( trailingslashit( $uploads ), trailingslashit( $this->get_base_parsaspace_url() ), $url );
		}
		return $url;
	}


	/**
	 * Wp Get Attachment SrcSet Url filter
	 *
	 * @since    1.0.0
	 */
	public function abc_custom_image_srcset( $sources, $size_array, $image_src, $image_meta, $attachment_id ) {
		$base_upload      = wp_upload_dir();
		$uploads          = $base_upload['baseurl'];
		$filtered_sources = array();
		foreach ( $sources as $key => $source ) {

			if ( wp_attachment_is_image( $attachment_id ) ) {
				$cdn = get_post_meta( $attachment_id, 'cdn_parsaspace', true );
				if ( $cdn != "" ) {
					$source['url'] = $url = str_replace( trailingslashit( $uploads ), trailingslashit( $this->get_base_parsaspace_url() ), $source['url'] );
				}
			}
			$filtered_sources[ $key ] = $source;
		}
		return $filtered_sources;
	}


	/**
	 * Check Is BaseFolder Define For ParsaSpace
	 *
	 * @since    1.0.0
	 */
	public function Check_is_Basefolder() {
		$opt = get_option( 'wp_parsaspace_opt' );
		if ( trim( $opt['base_folder'] ) != "" ) {
			return '/' . $opt['base_folder'];
		} else {
			return false;
		}
	}


	/**
	 * Create SubDir Wordpress In ParsaSpace
	 *
	 * @since    1.0.0
	 */
	public function Create_SubDir( $api ) {

		//Load Helper
		$helper = new Wp_Parsaspace_Helper();

		//Get Base Dir Upload in Wordpress
		$upload_dir = wp_upload_dir();
		$subdir     = str_replace( "\\", "/", $upload_dir['subdir'] );

		//Create Subfolder in Api
		$r               = explode( "/", $helper->remove_duplicate_slash( $subdir ) );
		$count           = count( $r );
		$get_before_path = "";

		//Check Basefolder
		$check_basefolder = $this->Check_is_Basefolder();
		if ( $check_basefolder !== false ) {
			$get_before_path = $check_basefolder;
		}

		for ( $i = 0; $i < $count; $i ++ ) {
			$api->CreateFolder( $helper->remove_duplicate_slash( $get_before_path . '/' . $r[ $i ] ) );
			$get_before_path .= $helper->remove_duplicate_slash( $r[ $i ] . "/" );
		}

		return $get_before_path;

	}


	/**
	 * Action Removed file From Wordpress
	 *
	 * @since    1.0.0
	 */
	public function wp_remove_file( $post_id ) {

		//Get File Path
		$file = get_attached_file( $post_id );

		//Get Upload Dir Base Wordpress
		$upload_dir = wp_upload_dir();

		//Create New Object Api
		$api = new Wp_Parsaspace_Api();

		//Check BaseFolder Api
		$check_basefolder = $this->Check_is_Basefolder();

		//check file in Parsaspace
		$is_in_parsaspace = get_post_meta( $post_id, 'cdn_parsaspace', true );
		if ( $is_in_parsaspace != "" ) {

			if ( wp_attachment_is_image( $post_id ) ) {

				/*
				 * If Attachment is Image
				 */
				$list = array();
				$args = get_post_meta( $post_id, '_wp_attachment_metadata', true );
				$path = str_replace( basename( $args['file'] ), "", $args['file'] );
				if ( $check_basefolder !== false ) {
					$path = $check_basefolder . '/' . $path;
				}
				$list[] = basename( $args['file'] );

				//Check if Extra Size Image
				if ( array_key_exists( "sizes", $args ) ) {
					foreach ( $args['sizes'] as $list_file ) {
						if ( $list_file['file'] != "" ) {
							$list[] = $list_file['file'];
						}
					}
				}

				//Remove file From ParsaSpace
				foreach ( $list as $file_name ) {
					$api->RemoveFile( $path . $file_name );
				}

			} else {

				/*
				 * If Attachment Not Image
				 */
				$file_path = str_replace( $upload_dir['basedir'], '', $file );
				if ( $check_basefolder !== false ) {
					$file_path = $check_basefolder . $file_path;
				}
				$api->RemoveFile( $file_path );

			}
		}

	}


	/**
	 * Action Upload file Non Image To ParsaSpace
	 *
	 * @since    1.0.0
	 */
	public function add_file_non_image_to_parsaspace( $post_id ) {

		$this->upload_file_to_parsaspace( $post_id );

		//Create Post Meta For Image File
		$opt = get_option( 'wp_parsaspace_opt' );
		if ( wp_attachment_is_image( $post_id ) and $opt['is_optimize'] == "no" ) {
			update_post_meta( $post_id, 'cdn_parsaspace', 1 );
		} else {
			//Add To Cron Optimize Image
			$file_id = $post_id;
			wp_schedule_single_event( time() + 120, 'cron_upload_image', array( $file_id ) );
		}

	}


	/**
	 * Action Upload Image file To ParsaSpace
	 *
	 * @since    1.0.0
	 */
	public function add_file_image_to_parsaspace( $args ) {

		//sleep for Run Query
		sleep( 2 );

		//Helper Load
		$helper = new Wp_Parsaspace_Helper();

		//Get Option For Check Optimize Plugin Active
		$opt = get_option( 'wp_parsaspace_opt' );

		//Get Upload Dir Base Wordpress
		$upload_dir = wp_upload_dir();
		$subdir     = str_replace( "\\", "/", $upload_dir['subdir'] );

		//Create New Object Api
		$api = new Wp_Parsaspace_Api();

		//Check BaseFolder Api
		$check_basefolder = $this->Check_is_Basefolder();

		//Create Subfolder in Api
		$subdir = $this->Create_SubDir( $api );

		//Check if Optimize Plugin Active
		$opt = get_option( 'wp_parsaspace_opt' );
		if ( $opt['is_optimize'] == "no" ) {

			/*
			* If Attachment is Image
			*/
			$list         = array();
			$path         = str_replace( basename( $args['file'] ), "", $args['file'] );
			$orginal_path = $path;
			if ( $check_basefolder !== false ) {
				$path = $check_basefolder . '/' . $path;
			}
			$list[] = basename( $args['file'] );

			//Check if Extra Size Image
			if ( array_key_exists( "sizes", $args ) ) {
				foreach ( $args['sizes'] as $list_file ) {
					if ( $list_file['file'] != "" ) {
						$list[] = $list_file['file'];
					}
				}
			}

			//Remove file From ParsaSpace
			foreach ( $list as $file_name ) {

				//RemoteUpload To Parsaspace
				$upload = $api->RemoteUpload( $path, $upload_dir['url'] . $helper->remove_duplicate_slash( '/' . basename( $file_name ) ) );

				//sleep for Run Query
				sleep( 3 );

				if ( $upload === true ) {

					//Remove File After Upload
					if ( $opt['is_optimize'] == "no" ) {
						$file_path = str_replace( "\\", "/", $upload_dir['path'] ) . $helper->remove_duplicate_slash( '/' . basename( $file_name ) );
						wp_schedule_single_event( time() + 120, 'cron_remove_attachment', array( $file_path ) );
					}

				}

			}

		}

		return $args;
	}


	/**
	 * Get Base Url ParsaSpace Service
	 *
	 * @since    1.0.0
	 */
	public function get_base_parsaspace_url() {

		//Get Option
		$opt = get_option( 'wp_parsaspace_opt' );

		//Check http
		$http = "http://";
		if ( $opt['is_ssl'] == "yes" ) {
			$http = "https://";
		}

		//Check Basefolder
		$base_folder = '';
		if ( trim( $opt['base_folder'] ) != "" ) {
			$base_folder = '/' . $opt['base_folder'];
		}


		return $http . $opt['domain_name'] . $base_folder;
	}


	/**
	 * Upload attachment Id To Parsaspace If Not Image
	 *
	 * @since    1.0.0
	 */
	public function upload_file_to_parsaspace( $post_id, $is_transfer = false ) {
		if ( wp_attachment_is_image( $post_id ) === false ) {

			//Load Helper
			$helper = new Wp_Parsaspace_Helper();

			//Get File Path
			$file = get_attached_file( $post_id );

			//Create New Object Api
			$api = new Wp_Parsaspace_Api();

			//Create Subfolder in Api
			if ( $is_transfer === true ) {

				//Get Upload Dir Base Wordpress
				$upload_dir = wp_upload_dir();
				$path       = str_replace( $upload_dir['basedir'], "", $file );
				$path       = str_replace( basename( $file ), "", $path );

				//Check BaseFolder Api
				$check_basefolder = $this->Check_is_Basefolder();
				if ( $check_basefolder !== false ) {
					$path = $helper->remove_duplicate_slash( $check_basefolder . '/' . $path );
				}
				$subdir          = $helper->remove_duplicate_slash( str_replace( "\\", "/", $path ) );
				$r               = explode( "/", $subdir );
				$count           = count( $r );
				$get_before_path = "";
				for ( $i = 0; $i < $count; $i ++ ) {
					$api->CreateFolder( $helper->remove_duplicate_slash( $get_before_path . '/' . $r[ $i ] ) );
					$get_before_path .= $helper->remove_duplicate_slash( $r[ $i ] . "/" );
				}

			} else {

				$subdir = $this->Create_SubDir( $api );

			}

			//Attachment Url
			$attachment_url = wp_get_attachment_url( $post_id );

			//RemoteUpload To Parsaspace
			$upload = $api->RemoteUpload( $subdir, $attachment_url );

			//sleep for Run Query
			sleep( 3 );

			//Helper Load
			$helper = new Wp_Parsaspace_Helper();

			//Get Beetween Path
			$between_path = $attachment_url;
			$between_path = str_ireplace( $upload_dir['baseurl'], "", $between_path );
			$between_path = str_ireplace( basename( $attachment_url ), "", $between_path );

			if ( $upload === true ) {

				//Create Post Meta For Image and Non Image File
				update_post_meta( $post_id, 'cdn_parsaspace', 1 );

				//change Url in Database
				$helper->search_and_replace( $attachment_url, $this->get_base_parsaspace_url() . $helper->remove_duplicate_slash( '/' . $between_path . '/' . basename( $attachment_url ) ) );

				//Remove File After Upload
				$file_path = $file;
				wp_schedule_single_event( time() + 60, 'cron_remove_attachment', array( $file_path ) );

			}

			return $upload;
		}
	}


	/**
	 * Cron Remove Attachment
	 *
	 * @since    1.0.0
	 */
	public function cron_remove_attachment( $file_path ) {
		wp_delete_file( $file_path );
	}


	/**
	 * Cron Image Upload Optimized
	 *
	 * @since    1.0.0
	 */
	public function cron_upload_image( $file_id ) {

		$post_id = $file_id;
		if ( wp_attachment_is_image( $post_id ) ) {

			//Get File Path
			$file = get_attached_file( $post_id );

			//Get Upload Dir Base Wordpress
			$upload_dir = wp_upload_dir();

			//Create New Object Api
			$api = new Wp_Parsaspace_Api();

			//Create New Object Helper
			$helper = new Wp_Parsaspace_Helper();

			//Check BaseFolder Api
			$check_basefolder = $this->Check_is_Basefolder();

			/*
			 * If Attachment is Image
			 */
			$list         = array();
			$args         = get_post_meta( $post_id, '_wp_attachment_metadata', true );
			$path         = str_replace( basename( $args['file'] ), "", $args['file'] );
			$orginal_path = $path;
			if ( $check_basefolder !== false ) {
				$path = $helper->remove_duplicate_slash( $check_basefolder . '/' . $path );
			}
			$list[] = basename( $args['file'] );

			//Check if Extra Size Image
			if ( array_key_exists( "sizes", $args ) ) {
				foreach ( $args['sizes'] as $list_file ) {
					if ( $list_file['file'] != "" ) {
						$list[] = $list_file['file'];
					}
				}
			}

			//Create folder in Parsaspace
			$subdir          = str_replace( "\\", "/", $path );
			$r               = explode( "/", $helper->remove_duplicate_slash( $subdir ) );
			$count           = count( $r );
			$get_before_path = "";
			for ( $i = 0; $i < $count; $i ++ ) {
				$api->CreateFolder( $helper->remove_duplicate_slash( $get_before_path . '/' . $r[ $i ] ) );
				$get_before_path .= $helper->remove_duplicate_slash( $r[ $i ] . "/" );
			}

			//Remove file From ParsaSpace
			foreach ( $list as $file_name ) {

				//RemoteUpload To Parsaspace
				$upload = $api->RemoteUpload( $path, $upload_dir['baseurl'] . '/' . $orginal_path . '/' . basename( $file_name ) );

				//sleep for Run Query
				sleep( 2 );

				if ( $upload === true ) {

					//Search and Replac in database
					$helper->search_and_replace( $upload_dir['baseurl'] . $helper->remove_duplicate_slash( '/' . $orginal_path . '/' . basename( $file_name ) ), $this->get_base_parsaspace_url() . $helper->remove_duplicate_slash( '/' . $orginal_path . '/' . basename( $file_name ) ) );

					//sleep for Run Query
					sleep( 2 );

					//Remove File
					$file_path = str_ireplace( basename( $file_name ), "", $file ) . basename( $file_name );
					wp_schedule_single_event( time() + 90, 'cron_remove_attachment', array( $file_path ) );

				}

			}

			//Add Post Meta
			update_post_meta( $post_id, 'cdn_parsaspace', 1 );
		}

	}


	/**
	 * Cron File Upload
	 *
	 * @since    1.0.0
	 */
	public function cron_upload_file( $file_id ) {
		$this->upload_file_to_parsaspace( $file_id, true );
	}


	/**
	 * Admin Notice
	 *
	 * @since    1.0.0
	 */
	public function admin_notices() {

		$helper = new Wp_Parsaspace_Helper();
		$screen = get_current_screen();

		/*
		 * Check Plugin Api Test Active
		 */
		$opt = get_option( 'wp_parsaspace_opt' );
		if ( $opt['is_active'] == "no" and $screen->id != 'toplevel_page_parsaspace_setting' ) {
			echo $helper->AdminNotice( __( 'Your website is not connected to the parsaspace Host', 'wp-parsaspace' ) . ', <a href="' . admin_url( 'admin.php?page=parsaspace_setting' ) . '">(' . __( 'Setting Page', 'wp-parsaspace' ) . ')</a>' );
		}


		/*
		 * Show Notice Error connection
		 */
		if ( isset( $_GET['conecct_error'] ) and $screen->id == 'toplevel_page_parsaspace_setting' ) {
			echo $helper->AdminNotice( 'متاسفانه ارتباط شما برقرار نشد , چنانچه از عدم اختلال در هاست خود مطمئن هستید با پشتیبانی پارسا اسپیس تماس حاصل فرمائید', 'error' );
		}


		/*
		 * Show Notice Success change Api token
		 */
		if ( isset( $_GET['change_success_token'] ) and $screen->id == 'toplevel_page_parsaspace_setting' ) {
			echo $helper->AdminNotice( 'شناسه API Token شما با موفقیت تغییر پیدا کرد', 'success' );
		}


		/*
		 * Show Notice Success Remote Url
		 */
		if ( isset( $_GET['remote_upload_file'] ) and $screen->id == 'toplevel_page_parsaspace_setting' ) {
			echo $helper->AdminNotice( 'درخواست دانلود فایل با موفقیت ارسال شد ، نتیجه را می توانید بعد از چند دقیقه در بخش مدیریت فایل ها مشاهده کنید.', 'success' );
		}


		/*
		* Show Notice Change Domain setting
		*/
		if ( isset( $_GET['change_domain_setting'] ) and $screen->id == 'toplevel_page_parsaspace_setting' ) {
			echo $helper->AdminNotice( 'تغییرات با موفقیت انجام شد', 'success' );
		}


		/*
		* Show Notice Remove Plugin setting
		*/
		if ( isset( $_GET['remove_plugin_setting'] ) and $screen->id == 'toplevel_page_parsaspace_setting' ) {
			echo $helper->AdminNotice( '
کاربر عزیز عملیات تغییر آدرس فایل ها و رسانه های وردپرس در وب سایت ، با موفقیت انجام شد.
<br>
دقت کنید ابتدا طبق آموزش انتقال فایل ها به وب سایت در زمان حذف افزونه  که در 
<a href="https://realwp.net/wp-parsaspace" target="_blank" class="text-danger no-dec">این صفحه</a>
 بطور کامل شرح داده شده ، مراحل را انجام دهید و سپس
افزونه را غیر فعال کنید
                ', 'success' );
		}


		/*
		 * Show Notice in Upload.php Action Bulk
		 */
		if ( isset( $_GET['bulk_upload_to_parsaspace'] ) and $screen->id == 'upload' ) {
			echo $helper->AdminNotice( sprintf( __( 'تعداد %d فایل به صف انتقال به پارسا اسپیس اضافه شد', 'wp-parsaspace' ), $_GET['bulk_upload_to_parsaspace'] ), 'success' );
		}

	}


	/**
	 * Add Script to Plugins.php For deactive Alert
	 *
	 * @since    1.0.0
	 */
	public function admin_print_scripts_plugin_deactive() {
		if ( is_plugin_active( 'wp-parsaspace/wp-parsaspace.php' ) ) {
			$helper = new Wp_Parsaspace_Helper();
			$helper->deactive_plugin_alert();
		}
	}


	/**
	 * Admin Footer Action
	 *
	 * @since    1.0.0
	 */
	public function admin_footer() {

		/*
		 * Check Plugin Install Step Show
		 */
		$opt = get_option( 'wp_parsaspace_opt' );
		if ( $opt['install_step'] == "yes" ) {

			$ui = new Wp_Parsaspace_Admin_Ui();
			$ui->install_step();

		}

	}


	/**
	 * Admin init
	 *
	 * @since    1.0.0
	 */
	public function admin_init() {
		global $pagenow;

		/*
		 * Disable install Step
		 */
		if ( isset( $_GET['disable_install_parsaspace'] ) and $_GET['disable_install_parsaspace'] == "yes" ) {
			$opt                 = get_option( "wp_parsaspace_opt" );
			$opt['install_step'] = "no";
			update_option( 'wp_parsaspace_opt', $opt );
		}

		/*
		 * If Api and domain is empty not allow Show setting page
		 */
		$opt = get_option( "wp_parsaspace_opt" );
		if ( $opt['install_step'] == "no" and $opt['api_token'] == "" and $opt['domain_name'] == "" and $pagenow == "admin.php" and isset( $_GET['page'] ) and $_GET['page'] == 'parsaspace_setting' ) {
			$opt['install_step'] = "yes";
			update_option( 'wp_parsaspace_opt', $opt );
			wp_redirect( admin_url( 'index.php' ) );
			exit;
		}

		/*
		 * Screen Layout Admin Page
		 */
		add_filter( 'screen_layout_columns', array( $this, 'on_screen_layout_columns' ), 10, 2 );
		add_action( 'admin_post_save_wp_parsaspace_metaboxes_general', array( $this, 'on_save_changes_layout_columns' ) );

	}


	/**
	 * Set Default Column Admin Page
	 *
	 * @since    1.0.0
	 */
	public function on_screen_layout_columns( $columns, $screen ) {
		//bugfix: $this->pagehook is not valid because it will be set at hook 'admin_menu' but
		//multisite pages or user dashboard pages calling different menu an menu hooks!
		if ( $screen == $this->pagehook ) {
			$columns[ $this->pagehook ] = 2;
		}
		return $columns;
	}


	/**
	 * Save Screen Layout
	 *
	 * @since    1.0.0
	 */
	public function on_save_changes_layout_columns() {
		//user permission check
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'Not Allowed !' ) );
		}
		//cross check the given referer
		check_admin_referer( 'wp-parsaspace_metaboxes-general' );

		//lets redirect the post request into get request (you may add additional params at the url, if you need to show save results
		wp_redirect( $_POST['_wp_http_referer'] );
	}


	/**
	 * Action Add Bulk Action upload.php
	 *
	 * @since    1.0.0
	 */
	public function bulk_actions_upload( $bulk_actions ) {
		$bulk_actions['upload_to_parsaspace'] = __( 'Transfer To Parsaspace', 'wp-parsaspace' );
		return $bulk_actions;
	}


	/**
	 * Handle Process Bulk Action upload.php
	 *
	 * @since    1.0.0
	 */
	public function handle_bulk_actions_upload( $redirect_to, $doaction, $post_ids ) {
		if ( $doaction !== 'upload_to_parsaspace' ) {
			return $redirect_to;
		}
		$time_out = 30;
		$number   = 0;
		foreach ( $post_ids as $post_id ) {
			if ( get_post_meta( $post_id, 'cdn_parsaspace', true ) == 1 ) {
			} else {
				$file_id = $post_id;
				$time    = time() + $time_out;

				//Add Cron Upload to ParsaSpace
				if ( wp_attachment_is_image( $post_id ) ) {
					wp_schedule_single_event( $time, 'cron_upload_image', array( $file_id ) );
				} else {
					wp_schedule_single_event( $time, 'cron_upload_file', array( $file_id ) );
				}

				$time_out = $time_out + 30;
				$number   = $number + 1;
			}
		}
		$redirect_to = add_query_arg( 'bulk_upload_to_parsaspace', $number, $redirect_to );
		return $redirect_to;
	}


	/**
	 * Action Add field To upload.php List Table
	 *
	 * @since    1.0.0
	 */
	public function manage_media_columns( $posts_columns ) {
		$posts_columns['place_file'] = __( 'File Place', 'wp-parsaspace' );
		return $posts_columns;
	}


	/**
	 * Check Attachment in Cron for Upload to Parsaspace
	 *
	 * @since    1.0.0
	 */
	public function check_attachment_in_cron( $post_id ) {
		$opt_cron = get_option( 'cron' );
		$ids      = array_column( $opt_cron, 'cron_upload_image' );
		$count    = count( $ids ) - 1;
		$list_key = array();
		for ( $x = 0; $x <= $count; $x ++ ) {
			$your_keys  = array_keys( $ids[ $x ] );
			$list_key[] = $your_keys;
		}

		$q = 0;
		foreach ( $list_key as $k ) {
			$key = $k[0];
			if ( $ids[ $q ][ $key ]['args'][0] == $post_id ) {
				return true;
			}
			$q ++;
		}

		return false;
	}


	/**
	 * Action Modify field To upload.php List Table
	 *
	 * @since    1.0.0
	 */
	public function manage_media_custom_column( $column_name, $post_id ) {

		if ( 'place_file' !== $column_name ) {
			return;
		}

		/*
		 * Place file column
		 */
		if ( get_post_meta( $post_id, 'cdn_parsaspace', true ) == 1 ) {

			echo __( 'ParsaSpace', 'wp-parsaspace' );

		} else {

			if ( $this->check_attachment_in_cron( $post_id ) === true ) {

				echo 'در صف انتقال به پارسااسپیس ...';

			} else {

				echo '<div data-upload-file="' . $post_id . '">';
				echo '<button type="button" data-file="' . $post_id . '" class="button button-secondary">' . __( 'Transfer To Parsaspace', 'wp-parsaspace' ) . '</button>';
				echo '</div>';

			}

		}

	}


	/**
	 * Add Style upload.php List Table
	 *
	 * @since    1.0.0
	 */
	public function admin_print_styles_upload_table() {
		echo
		'<style>
		.fixed .column-place_file {
			width: 15%;
		}
	</style>';
	}


	/**
	 * Change Jpeg Quality in not compress Plugin
	 *
	 * @since    1.0.0
	 */
	public function jpeg_quality() {
		$opt = get_option( 'wp_parsaspace_opt' );
		return $opt['quality_jpg'];
	}


	/**
	 * Change Max Upload Size in Wordpress
	 *
	 * @since    1.0.0
	 */
	public function upload_size_limit( $size ) {
		$opt  = get_option( 'wp_parsaspace_opt' );
		$size = 1024 * 1024 * ( $opt['max_size_upload'] );
		return $size;
	}


}

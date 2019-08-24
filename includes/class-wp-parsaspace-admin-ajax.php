<?php

class Wp_Parsaspace_Admin_Ajax {

	private $wpdb;

	public function __construct() {

		$this->wpdb = &$GLOBALS['wpdb'];

		$list_function = [
			'install_parsaspace',
			'transfer_to_parsaspace',
			'test_connection_to_parsaspace',
			'change_api_token_parsaspace',
			'change_setting_domain_parsaspace',
			'reset_all_url_in_db_parsaspace',
			'change_parsaspace_setting_form',
			'request_remote_url_to_parsaspace',
			'wp_parsaspace_filemanager_api',
			'wp_remove_path_parsaspace',
		];

		foreach ( $list_function as $method ) {
			add_action( 'wp_ajax_' . $method, array( $this, $method ) );
		}

	}


	/**
	 * install ParsaSpace
	 *
	 * @since    1.0.0
	 */
	public function install_parsaspace() {

		global $wpdb;
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

			//Check Admin Reffer
			check_ajax_referer( 'wp_parsaspace_token', 'security' );

			//Heper function
			$helper = new Wp_Parsaspace_Helper();

			//check Empty Token
			if ( trim( $_GET['token_api'] ) == "" ) {
				$result = array( 'error' => 'yes', 'text' => 'لطفا API Token را وارد نمایید', );
				$helper->json_exit( $result );
			}

			//check Domain Name
			if ( trim( $_GET['domain_name'] ) == "" ) {
				$result = array( 'error' => 'yes', 'text' => 'لطفا نام دامنه را وارد نمایید' );
				$helper->json_exit( $result );
			}

			//Get domain Name
			$domain_name = $helper->remove_slash( str_replace( "https://", "", str_replace( "http://", "", trim( $_GET['domain_name'] ) ) ) );

			//Get Path BaseFolder
			$base_folder = $helper->remove_slash( trim( $_GET['base_folder'] ) );

			//check Image Quality
			if ( trim( $_GET['quality_jpg'] ) == "" ) {
				$result = array( 'error' => 'yes', 'text' => 'لطفا اندازه کیفیت عکس را وارد نمایید' );
				$helper->json_exit( $result );
			}

			//Image Quality only Number
			if ( is_numeric( trim( $_GET['quality_jpg'] ) ) === false ) {
				$result = array( 'error' => 'yes', 'text' => 'اندازه کیفیت عکس تنها شامل اعداد می باشد' );
				$helper->json_exit( $result );
			}

			//image Quality > 100
			if ( trim( $_GET['quality_jpg'] ) > 100 ) {
				$result = array( 'error' => 'yes', 'text' => 'اندازه کیفیت عکس نباید بیش تر از 100 باشد' );
				$helper->json_exit( $result );
			}

			//check Max Upload
			if ( trim( $_GET['max_size_upload'] ) == "" ) {
				$result = array( 'error' => 'yes', 'text' => 'لطفا حداکثر حجم آپلود فایل را وارد نمایید' );
				$helper->json_exit( $result );
			}

			//Max Upload only Number
			if ( is_numeric( trim( $_GET['max_size_upload'] ) ) === false ) {
				$result = array( 'error' => 'yes', 'text' => 'حداکثر حجم آپلود فایل تنها شامل عدد می باشد' );
				$helper->json_exit( $result );
			}

			//Check Connection Api
			$api = new Wp_Parsaspace_Api();
			if ( $api->TestApi( trim( $_GET['token_api'] ), $domain_name ) === false ) {
				$result = array( 'error' => 'yes', 'text' => 'ارتباط شما با پارسا اسپیس برقرار نشد لطفا مقادیر ورودی را بررسی کنید' );
				$helper->json_exit( $result );
			}

			//Option Update
			$opt                    = get_option( 'wp_parsaspace_opt' );
			$opt['api_token']       = trim( $_GET['token_api'] );
			$opt['domain_name']     = $domain_name;
			$opt['is_ssl']          = trim( $_GET['is_ssl'] );
			$opt['base_folder']     = $base_folder;
			$opt['remote_dir']      = 'direct';
			$opt['install_step']    = 'no';
			$opt['is_active']       = 'yes';
			$opt['is_optimize']     = trim( $_GET['is_optimize'] );
			$opt['quality_jpg']     = trim( $_GET['quality_jpg'] );
			$opt['max_size_upload'] = trim( $_GET['max_size_upload'] );
			//$opt['is_automatic_upload'] = trim( $_GET['is_automatic_upload'] );
			$opt['is_automatic_upload'] = 'no';
			update_option( 'wp_parsaspace_opt', $opt );


			$result = array(
				'error'    => 'no',
				'text'     => 'Proccess is Ok',
				'redirect' => admin_url( 'admin.php?page=parsaspace_setting' ),
			);
			$helper->json_exit( $result );
		}
		die();

	}


	/**
	 * Change Parsaspace Setting form
	 *
	 * @since    1.0.0
	 */
	public function change_parsaspace_setting_form() {

		global $wpdb;
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

			//Check Admin Reffer
			check_ajax_referer( 'wp_parsaspace_token', 'security' );

			//Helper function
			$helper = new Wp_Parsaspace_Helper();
			$opt    = get_option( 'wp_parsaspace_opt' );

			//Check Remote Dir
			if ( trim( $_GET['remote_dir'] ) == "" ) {
				$result = array( 'error' => 'yes', 'text' => 'لطفا پوشه Remote File را وارد کنید' );
				$helper->json_exit( $result );
			}

			//Remote File Not Wordpress folder
			$remote_dir = $helper->remove_slash( trim( $_GET['remote_dir'] ) );
			if ( $remote_dir == $opt['base_folder'] ) {
				$result = array( 'error' => 'yes', 'text' => 'پوشه Remote File نمی تواند با فولدر اصلی فایل های وردپرس در پارسا اسپیس یکی باشد' );
				$helper->json_exit( $result );
			}

			//check Image Quality
			if ( trim( $_GET['quality_jpg'] ) == "" ) {
				$result = array( 'error' => 'yes', 'text' => 'لطفا اندازه کیفیت عکس را وارد نمایید' );
				$helper->json_exit( $result );
			}

			//Image Quality only Number
			if ( is_numeric( trim( $_GET['quality_jpg'] ) ) === false ) {
				$result = array( 'error' => 'yes', 'text' => 'اندازه کیفیت عکس تنها شامل اعداد می باشد' );
				$helper->json_exit( $result );
			}

			//image Quality > 100
			if ( trim( $_GET['quality_jpg'] ) > 100 ) {
				$result = array( 'error' => 'yes', 'text' => 'اندازه کیفیت عکس نباید بیش تر از 100 باشد' );
				$helper->json_exit( $result );
			}

			//check Max Upload
			if ( trim( $_GET['max_size_upload'] ) == "" ) {
				$result = array( 'error' => 'yes', 'text' => 'لطفا حداکثر حجم آپلود فایل را وارد نمایید' );
				$helper->json_exit( $result );
			}

			//Max Upload only Number
			if ( is_numeric( trim( $_GET['max_size_upload'] ) ) === false ) {
				$result = array( 'error' => 'yes', 'text' => 'حداکثر حجم آپلود فایل تنها شامل عدد می باشد' );
				$helper->json_exit( $result );
			}


			//Option Update
			$opt['is_optimize']     = trim( $_GET['is_optimize'] );
			$opt['quality_jpg']     = trim( $_GET['quality_jpg'] );
			$opt['max_size_upload'] = trim( $_GET['max_size_upload'] );
			//$opt['is_automatic_upload'] = trim( $_GET['is_automatic_upload'] );
			$opt['is_automatic_upload'] = 'no';
			$opt['remote_dir']          = $remote_dir;
			update_option( 'wp_parsaspace_opt', $opt );


			$result = array(
				'error' => 'no',
				'text'  => 'Proccess is Ok',
			);
			$helper->json_exit( $result );
		}
		die();

	}


	/**
	 * Transfer To Parsaspace File
	 *
	 * @since    1.0.0
	 */
	public function transfer_to_parsaspace() {

		global $wpdb;
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

			//Check Admin Reffer
			check_ajax_referer( 'wp_parsaspace_token', 'security' );

			//Heper function
			$admin  = new Wp_Parsaspace_Admin( 'wp-parsaspace', WP_PARSASPACE_VERSION );
			$helper = new Wp_Parsaspace_Helper();

			//Post Id
			$post_id = trim( $_GET['post_id'] );
			if ( wp_attachment_is_image( $post_id ) ) {
				$admin->cron_upload_image( $post_id );
			} else {
				$admin->upload_file_to_parsaspace( $post_id, true );
			}

			$result = array(
				'error' => 'no',
				'text'  => 'Process is Ok',
			);
			$helper->json_exit( $result );
		}
		die();

	}


	/**
	 * Test Connect To ParsaSpace
	 *
	 * @since    1.0.0
	 */
	public function test_connection_to_parsaspace() {

		global $wpdb;
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

			//Check Admin Reffer
			check_ajax_referer( 'wp_parsaspace_token', 'security' );

			$helper   = new Wp_Parsaspace_Helper();
			$api      = new Wp_Parsaspace_Api();
			$test_con = $api->TestApi();
			if ( $test_con === false ) {
				$result = array(
					'error'    => 'yes',
					'redirect' => admin_url( 'admin.php?page=parsaspace_setting&conecct_error=yes' ),
				);
				$helper->json_exit( $result );
			}

			$result = array(
				'error' => 'no',
				'text'  => 'ارتباط وب سایت شما با سرور پارسا اسپیس کاملا برقرار است',
			);
			$helper->json_exit( $result );
		}
		die();

	}


	/**
	 * Change Api Token ParsaSpace
	 *
	 * @since    1.0.0
	 */
	public function change_api_token_parsaspace() {

		global $wpdb;
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

			//Check Admin Reffer
			check_ajax_referer( 'wp_parsaspace_token', 'security' );

			//Check Api Token $_GET
			if ( ! isset( $_GET['api_token'] ) ) {
				exit;
			}

			$helper = new Wp_Parsaspace_Helper();

			//Check Empty
			if ( trim( $_GET['api_token'] ) == "" ) {
				$result = array(
					'error' => 'yes',
					'text'  => 'لطفا API Token را وارد نمایید',
				);
				$helper->json_exit( $result );
			}

			//Check New Token
			$api = new Wp_Parsaspace_Api();

			$test_con = $api->TestApi( trim( $_GET['api_token'] ), false );
			if ( $test_con === false ) {
				$result = array(
					'error' => 'yes',
					'text'  => 'مقدار Token وارد شده معتبر نمی باشد و سایت نمی تواند به پارسا اسپیس متصل شود',
				);
				$helper->json_exit( $result );
			}

			//Change Token
			$opt              = get_option( 'wp_parsaspace_opt' );
			$opt['api_token'] = trim( $_GET['api_token'] );
			update_option( 'wp_parsaspace_opt', $opt );


			$result = array(
				'error'    => 'no',
				'redirect' => admin_url( 'admin.php?page=parsaspace_setting&change_success_token=yes' ),
			);
			$helper->json_exit( $result );
		}
		die();

	}


	/**
	 * Change Domain Setting
	 *
	 * @since    1.0.0
	 */
	public function change_setting_domain_parsaspace() {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

			//Check Admin Reffer
			check_ajax_referer( 'wp_parsaspace_token', 'security' );

			//Helper function
			$helper = new Wp_Parsaspace_Helper();

			//Get Before Address Attachment
			$admin      = new Wp_Parsaspace_Admin( 'wp-parsaspace', WP_PARSASPACE_VERSION );
			$before_url = $admin->get_base_parsaspace_url();

			//Check Domain Name
			if ( trim( $_GET['domain_name'] ) == "" ) {
				$result = array( 'error' => 'yes', 'text' => 'لطفا نام دامنه را وارد نمایید' );
				$helper->json_exit( $result );
			}

			//Check Token
			if ( trim( $_GET['token'] ) == "" ) {
				$result = array( 'error' => 'yes', 'text' => 'لطفا توکن را وارد نمایید' );
				$helper->json_exit( $result );
			}

			//Get domain Name
			$domain_name = $helper->remove_slash( str_replace( "https://", "", str_replace( "http://", "", trim( $_GET['domain_name'] ) ) ) );

			//Get Path BaseFolder
			$base_folder = $helper->remove_slash( trim( $_GET['base_folder'] ) );

			//Check Connection Api
			$api = new Wp_Parsaspace_Api();
			if ( $api->TestApi( trim( $_GET['token'] ), $domain_name ) === false ) {
				$result = array( 'error' => 'yes', 'text' => 'ارتباط شما با پارسا اسپیس برقرار نشد لطفا مقادیر ورودی را بررسی کنید' );
				$helper->json_exit( $result );
			}

			$is_cron_change_update_link = false;
			$opt                        = get_option( 'wp_parsaspace_opt' );

			//Change folder Name if is Changed
			if ( $base_folder != $opt['base_folder'] ) {
				$api->Rename( "/" . $opt['base_folder'], "/" . $base_folder );
				$is_cron_change_update_link = true;
			}

			//change if domain Name is Changed
			if ( $domain_name != $opt['domain_name'] ) {
				$is_cron_change_update_link = true;
			}

			//Option Update
			$opt['api_token']   = trim( $_GET['token'] );
			$opt['domain_name'] = $domain_name;
			$opt['is_ssl']      = trim( $_GET['is_ssl'] );
			$opt['base_folder'] = $base_folder;
			update_option( 'wp_parsaspace_opt', $opt );


			/*
			 * change Url Cron Start in Website
			 */
			if ( $is_cron_change_update_link === true ) {

				//Get New Url
				$http = "http://";
				if ( trim( $_GET['is_ssl'] ) == "yes" ) {
					$http = "https://";
				}
				$base_folder_new = '';
				if ( trim( $base_folder ) != "" ) {
					$base_folder_new = '/' . $opt['base_folder'];
				}
				$new_url = $http . $domain_name . $base_folder_new;

				//Change Url in Database
				$helper->search_and_replace( $before_url, $new_url );
			}


			$redirect = admin_url( 'admin.php?page=parsaspace_setting&change_domain_setting=true' );
			$result   = array(
				'error'    => 'no',
				'text'     => 'Proccess is Ok',
				'redirect' => $redirect,
			);
			$helper->json_exit( $result );
		}
		die();

	}


	/**
	 * Change Domain Setting
	 *
	 * @since    1.0.0
	 */
	public function reset_all_url_in_db_parsaspace() {

		global $wpdb;
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

			//Check Admin Reffer
			check_ajax_referer( 'wp_parsaspace_token', 'security' );

			//Heper function
			$helper = new Wp_Parsaspace_Helper();

			//Get Before Address Attachment
			$admin      = new Wp_Parsaspace_Admin( 'wp-parsaspace', WP_PARSASPACE_VERSION );
			$before_url = $admin->get_base_parsaspace_url();

			/*
			 * Remove All Post Meta ParsaSpace
			 */
			$wpdb->query( "DELETE FROM `" . $wpdb->postmeta . "` WHERE `meta_key` = 'cdn_parsaspace'" );

			//Get New Url
			$upload_dir = wp_upload_dir();
			$new_url    = $upload_dir['baseurl'];

			//Change Url in Database
			$helper->search_and_replace( $before_url, $new_url );

			$result = array(
				'error' => 'no',
				'text'  => 'Proccess is Ok',
			);
			$helper->json_exit( $result );
		}
		die();

	}


	/**
	 * Remote Url Send Request
	 *
	 * @since    1.0.0
	 */
	public function request_remote_url_to_parsaspace() {

		global $wpdb;
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

			//Check Admin Reffer
			check_ajax_referer( 'wp_parsaspace_token', 'security' );

			//Heper function
			$helper = new Wp_Parsaspace_Helper();

			//Check Not $_GET[]
			if ( ! isset( $_GET['file_url'] ) ) {
				exit;
			}
			if ( ! isset( $_GET['file_name'] ) ) {
				exit;
			}

			$file_url  = trim( $_GET['file_url'] );
			$file_name = trim( $_GET['file_name'] );

			//Check file Url
			if ( $file_url == "" ) {
				$result = array( 'error' => 'yes', 'text' => 'لطفا آدرس فایل را وارد نمایید' );
				$helper->json_exit( $result );
			}


			//Check File Name
			if ( $file_name == "" ) {
				/* Nothing */
			} else {

				//Sanitize file Name
				$file_name = sanitize_file_name( strtolower( $_GET['file_name'] ) );

				//Check Ext in filename [sanitize Shode]
				if ( $file_name == "" ) {
					$result = array( 'error' => 'yes', 'text' => 'نام فایل شامل کاراکتر های غیر مجاز هست لطفا اصلاح کنید' );
					$helper->json_exit( $result );
				}

				//Check Ext File
				if ( stristr( $file_name, "." ) === false ) {
					$result = array( 'error' => 'yes', 'text' => 'پسوند فایل نامشخص هست' );
					$helper->json_exit( $result );
				}

				//Get Only File Name without ext
				$ext_upload            = explode( ".", $file_name );
				$file_name_without_ext = $ext_upload[0];

				//Check Valid File Name
				if ( preg_match( '/^[a-z0-9-]+$/', $file_name_without_ext ) ) {
					/* nothing */
				} else {
					$result = array( 'error' => 'yes', 'text' => 'نام فایل شامل کاراکتر های غیر مجاز هست لطفا اصلاح کنید' );
					$helper->json_exit( $result );
				}

			}

			//Check Exist File
			if ( $helper->curl_get_file_size( $file_url ) == 0 ) {
				$result = array( 'error' => 'yes', 'text' => 'آدرس فایل وارد شده معتبر نمی باشد و یا توسط سرویس دهنده قابل دسترس نیست لطفا دوباره تلاش کنید' );
				$helper->json_exit( $result );
			}

			//Get Request File
			$api  = new Wp_Parsaspace_Api();
			$opt  = get_option( 'wp_parsaspace_opt' );
			$path = "/" . $opt['remote_dir'] . "/";
			$api->CreateFolder( "/" . $opt['remote_dir'] );
			$api->RemoteUpload( $helper->remove_duplicate_slash( $path ), $file_url );

			if ( $file_name == "" ) {
				sleep( 8 );
			} else {
				sleep( 9 );
				//Change File Name
				$api->Rename( $helper->remove_duplicate_slash( rtrim( $path, "/" ) . "/" . basename( $file_url ) ), $helper->remove_duplicate_slash( rtrim( $path, "/" ) . "/" . $file_name ) );
				sleep( 2 );
			}

			$result = array(
				'error'    => 'no',
				'text'     => 'Proccess is Ok',
				'redirect' => admin_url( 'admin.php?page=parsaspace_setting&remote_upload_file=true' ),
			);
			$helper->json_exit( $result );
		}
		die();

	}


	/**
	 * File Manager Show Api
	 *
	 * @since    1.0.0
	 */
	public function wp_parsaspace_filemanager_api() {

		global $wpdb;
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

			//Check Admin Reffer
			check_ajax_referer( 'wp_parsaspace_token', 'security' );

			//Check Step Get
			if ( ! isset( $_GET['step'] ) ) {
				exit;
			}

			//Heper function
			$helper = new Wp_Parsaspace_Helper();
			$api    = new Wp_Parsaspace_Api();


			/*
			 * File Manager Step
			 */
			if ( trim( $_GET['step'] ) == "file_manager" ) {

				$path = trim( $_GET['path'] );
				$text = '';

				/*BreadCrumb*/
				$breadcrumb = "";
				//if($path !="/") {
				$q        = 1;
				$saf_path = "/";
				$count    = count( explode( "/", $path ) );
				foreach ( explode( "/", $path ) as $h ) {
					if ( $q == 1 ) {
						$breadcrumb .= ' <span class="go-folder" data-show-parsaspace-path="/"><i class="fa fa-home" style="font-size: 18px;vertical-align: -0.5px;margin-right: 3px;color: #444444;"></i> Root</span><span class="go-sep">/</span>';
						$saf_path   .= $saf_path . '/' . $h . '/';
					} else {
						if ( $h != "" ) {
							if ( $q != $count ) {
								$breadcrumb .= ' <span class="go-folder" data-show-parsaspace-path="' . preg_replace( '/(\/+)/', '/', $saf_path . '/' . $h . '/' ) . '">' . $h . '</span><span class="go-sep">/</span>';
								if ( $q > 1 ) {
									$saf_path .= $saf_path . '/' . $h . '/';
								}
							}
						}
					}
					$q ++;
				}
				//}


				//Title
				$text .= '<div class="title">
                    <div class="f-right">
                    <i class="fa fa-cloud"></i> مدیریت فایل پارسا اسپیس
                    </div>
                    <div class="f-left trebuchet-font" dir="ltr">
                    ' . $breadcrumb . '
                    </div>
                    <div class="clearfix"></div>
                    </div>';


				//Table Show
				$text .= '
                <table id="tbl_parsaspace_manager">
                    <tr>
                        <td width="50" class="text-center"></td>
                        <td width="50" class="text-center">دریافت فایل</td>
                        <td width="70" class="text-center">حجم فایل</td>
                        <td width="100" class="text-center">آخرین بروز رسانی</td>
                        <td class="text-center">نام فایل</td>
                    </tr>';

				//Show List File
				$list_file = $api->GetListFile( $path );
				if ( $list_file === false ) {
					$result = array(
						'error' => 'yes',
						'text'  => "Problem in Connect to ParsaSpace",
					);
					$helper->json_exit( $result );
				} else {

					$count_item = count( $list_file );
					if ( $count_item == 0 ) {

						$text .= '
                        <tr>
                            <td colspan="5" class="text-center">هیچ فایلی در این پوشه یافت نشد</td>
                        </tr>
                        ';

					} else {

						//Include Parsidate
						require_once( WP_PARSASPACE_DIR_PATH . 'includes/class-wp-parsaspace-parsidate.php' );
						//Get Option
						$opt  = get_option( 'wp_parsaspace_opt' );
						$http = "http://";
						if ( $opt['is_ssl'] == "yes" ) {
							$http = "https://";
						}
						$domain = $http . $opt['domain_name'];

						foreach ( $list_file as $l ) {


							//time
							$ext_date   = explode( "T", $l['LastModified'] );
							$date       = $ext_date[0];
							$ext_modi   = explode( "T", $l['LastModified'] );
							$ext_time   = explode( ".", $ext_modi[1] );
							$time       = $ext_time[0];
							$merge_time = $date . " " . $time;

							//Base Folder
							$base_folder        = "/" . $opt['base_folder'] . "/";
							$base_remote_folder = "/" . $opt['remote_dir'] . "/";


							if ( $l['IsFolder'] === true ) {

								/*filter Show Folder*/
								if ( $l['Name'] != ".well-known" ) {

									$folder_path = preg_replace( '/(\/+)/', '/', trim( $_GET['path'] ) . '/' . $l['Name'] . '/' );

									/*Not Alow Delete Base Folder*/
									$remove_icon = '<span data-delete-parsaspace="' . $folder_path . '"><i class="fa fa-trash cp"></i></span>';

									if ( $folder_path == $base_folder || $folder_path == $base_remote_folder ) {
										$remove_icon = '';
									}
									if ( strpos( $folder_path, $base_folder ) === 0 ) {
										$remove_icon = '';
									}


									$text .= '
                      <tr>

                        <td class="text-center">' . $remove_icon . '</td>
                        <td class="text-center">-</td>
                        <td class="text-center"><span class="trebuchet-font" dir="ltr">-</span></td>
                        <td class="text-center">' . parsidate( "Y-m-d ساعت H:i", $merge_time, "per" ) . '</td>
                        <td class="text-left" dir="ltr" width="300"><i class="fa fa-folder ico-fo text-warning"></i>&nbsp; <span class="trebuchet-font text-warning" data-show-parsaspace-path="' . $folder_path . '">' . $l['Name'] . '</span></td>

                    </tr>
                                ';

								}

							} else {

								$file_link = $domain . preg_replace( '/(\/+)/', '/', trim( $_GET['path'] ) . $l['Name'] );
								$file_path = preg_replace( '/(\/+)/', '/', trim( $_GET['path'] ) . '/' . $l['Name'] );
								/*Not Alow Delete Base Folder*/
								$remove_icon = '<span data-delete-parsaspace="' . $file_path . '"><i class="fa fa-trash cp"></i></span>&nbsp;&nbsp;';
								if ( strpos( $file_path, $base_folder ) === 0 ) {
									$remove_icon = '';
								}

								$text .= '
                     <tr>
                        <td class="text-center">' . $remove_icon . ' <span data-alert-url-parsaspace="' . $file_link . '"><i class="fa fa-copy cp"></i></span></td>
                         <td class="text-center"><a href="' . $file_link . '" target="_blank"><i class="fa fa-download dl-link-table"></i></a></td>
                        <td class="text-center"><span class="trebuchet-font" dir="ltr">' . size_format( $l['Size'], 2 ) . '</span></td>
                        <td class="text-center">' . parsidate( "Y-m-d ساعت H:i", $merge_time, "per" ) . '</td>
                        <td class="text-left" dir="ltr" width="300"><i class="fa fa-' . $helper->mimetype2FontAwesome( $helper->get_mime_type( $l['Name'] ) ) . ' ico-fo"></i> &nbsp; <span class="trebuchet-font">' . $l['Name'] . '</span></td>
                    </tr>
                                ';
							}

						}

					}
				}
				$text .= '</table>';

				$result = array(
					'error' => 'no',
					'text'  => $text,
					'path'  => trim( $_GET['path'] ),
				);
				$helper->json_exit( $result );

			}


			$result = array(
				'error' => 'yes',
				'text'  => 'Error Process',
			);
			$helper->json_exit( $result );
		}
		die();

	}


	/**
	 * Remove file Or folder Request
	 *
	 * @since    1.0.0
	 */
	public function wp_remove_path_parsaspace() {

		global $wpdb;
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

			//Check Admin Reffer
			check_ajax_referer( 'wp_parsaspace_token', 'security' );

			//Heper function
			$helper = new Wp_Parsaspace_Helper();

			//Check Not $_GET[]
			if ( ! isset( $_GET['path_remove'] ) ) {
				exit;
			}

			$path_url = trim( $_GET['path_remove'] );

			//Get Request File
			$api = new Wp_Parsaspace_Api();

			//Remove
			$remove_api = $api->RemoveFile( $path_url );
			if ( $remove_api === false ) {
				$result = array(
					'error' => 'yes',
					'text'  => 'درخواست حذف انجام نشد لطفا دوباره تلاش کنید',
				);
				$helper->json_exit( $result );
			}

			$result = array(
				'error' => 'no',
				'text'  => 'Proccess is Ok',
			);
			$helper->json_exit( $result );
		}
		die();

	}


}

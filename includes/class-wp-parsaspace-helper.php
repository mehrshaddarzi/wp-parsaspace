<?php

class Wp_Parsaspace_Helper {


	/**
	 * Admin Notice Show
	 *
	 * @since    1.0.0
	 */
	public function AdminNotice( $text, $model = "info", $close_button = true, $style_extra = 'padding: 12px;' ) {
		/*
		 * List of Model : error / warning / success / info
		 */
		return '
        <div class="notice notice-' . $model . '' . ( $close_button === true ? " is-dismissible" : "" ) . '">
            <div style="' . $style_extra . '">
            ' . $text . '
            </div>
        </div>
        ';
	}


	/**
	 * Show Json and Exit
	 *
	 * @since    1.0.0
	 */
	public function json_exit( $array ) {
		wp_send_json( $array );
		exit;
	}


	/**
	 * Remove Slash From String
	 *
	 * @since    1.0.0
	 */
	public function remove_slash( $string ) {
		return str_replace( "\\", "", str_replace( "/", "", $string ) );
	}


	/**
	 * Remove Two Slash in Url
	 *
	 * @since    1.0.0
	 */
	public function remove_duplicate_slash( $string ) {
		return str_replace( "//", "/", $string );
	}


	/**
	 * Get Size File From Url With Curl
	 *
	 * @since    1.0.0
	 */
	public function curl_get_file_size( $url ) {
		$ch = curl_init( $url );
		curl_setopt( $ch, CURLOPT_NOBODY, 1 );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 0 );
		curl_setopt( $ch, CURLOPT_HEADER, 0 );
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 );
		curl_setopt( $ch, CURLOPT_MAXREDIRS, 3 );
		curl_exec( $ch );
		$filesize = curl_getinfo( $ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD );
		curl_close( $ch );
		if ( $filesize ) {
			return $filesize;
		} else {
			return 0;
		}
	}


	/**
	 * Get Mime Type By Type
	 */
	public function get_mime_type( $filename ) {
		$idx           = explode( '.', $filename );
		$count_explode = count( $idx );
		$idx           = strtolower( $idx[ $count_explode - 1 ] );

		$mimet = array(
			'txt'  => 'text/plain',
			'htm'  => 'text/html',
			'html' => 'text/html',
			'php'  => 'text/html',
			'css'  => 'text/css',
			'js'   => 'application/javascript',
			'json' => 'application/json',
			'xml'  => 'application/xml',
			'swf'  => 'application/x-shockwave-flash',
			'flv'  => 'video/x-flv',

			// images
			'png'  => 'image/png',
			'jpe'  => 'image/jpeg',
			'jpeg' => 'image/jpeg',
			'jpg'  => 'image/jpeg',
			'gif'  => 'image/gif',
			'bmp'  => 'image/bmp',
			'ico'  => 'image/vnd.microsoft.icon',
			'tiff' => 'image/tiff',
			'tif'  => 'image/tiff',
			'svg'  => 'image/svg+xml',
			'svgz' => 'image/svg+xml',

			// archives
			'zip'  => 'application/zip',
			'rar'  => 'application/x-rar-compressed',
			'exe'  => 'application/x-msdownload',
			'msi'  => 'application/x-msdownload',
			'cab'  => 'application/vnd.ms-cab-compressed',

			// audio/video
			'mp3'  => 'audio/mpeg',
			'qt'   => 'video/quicktime',
			'mov'  => 'video/quicktime',

			// adobe
			'pdf'  => 'application/pdf',
			'psd'  => 'image/vnd.adobe.photoshop',
			'ai'   => 'application/postscript',
			'eps'  => 'application/postscript',
			'ps'   => 'application/postscript',

			// ms office
			'doc'  => 'application/msword',
			'rtf'  => 'application/rtf',
			'xls'  => 'application/vnd.ms-excel',
			'ppt'  => 'application/vnd.ms-powerpoint',
			'docx' => 'application/msword',
			'xlsx' => 'application/vnd.ms-excel',
			'pptx' => 'application/vnd.ms-powerpoint',


			// open office
			'odt'  => 'application/vnd.oasis.opendocument.text',
			'ods'  => 'application/vnd.oasis.opendocument.spreadsheet',
		);

		if ( isset( $mimet[ $idx ] ) ) {
			return $mimet[ $idx ];
		} else {
			return 'application/octet-stream';
		}
	}


	/**
	 * Turn mimetype into font awesome file icon name (e.g. file-text-o)
	 * @param  string $mimetype File mimetype
	 * @return string           Font Awesome file icon name
	 */
	function mimetype2FontAwesome( $mimetype = null ) {
		switch ( $mimetype ) {
			// PDF
			case 'application/pdf':
				return 'file-pdf-o';
				break;
			// Plain text
			case 'text/plain':
				return 'file-text-o';
				break;
			// Audio
			case 'audio/basic':
			case 'audio/L24':
			case 'audio/mp4':
			case 'audio/mpeg':
			case 'audio/ogg':
			case 'audio/flac':
			case 'audio/opus':
			case 'audio/vorbis':
			case 'audio/vnd.rn-realaudio':
			case 'audio/vnd.wave':
			case 'audio/webm':
			case 'audio/x-aac':
			case 'audio/x-caf':
				return 'file-audio-o';
				break;
			// Video
			case 'video/avi':
			case 'video/mpeg':
			case 'video/mp4':
			case 'video/ogg':
			case 'video/quicktime':
			case 'video/webm':
			case 'video/x-matroska':
			case 'video/x-ms-wmv':
			case 'video/x-fkv':
				return 'file-video-o';
				break;
			// Powerpoint
			case 'application/vnd.ms-powerpoint':
			case 'application/vnd.openxmlformats-officedocument.presentationml.presentation':
			case 'application/vnd.openxmlformats-officedocument.presentationml.template':
			case 'application/vnd.openxmlformats-officedocument.presentationml.slideshow':
			case 'application/vnd.ms-powerpoint.addin.macroEnabled.12':
			case 'application/vnd.ms-powerpoint.presentation.macroEnabled.12':
			case 'application/vnd.ms-powerpoint.template.macroEnabled.12':
			case 'application/vnd.ms-powerpoint.slideshow.macroEnabled.12':
				return 'file-powerpoint-o';
				break;
			// Word
			case 'application/msword':
			case 'application/vnd.openxmlformats-officedocument.wordprocessingml.template':
			case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
			case 'application/vnd.ms-word.document.macroEnabled.12':
			case 'application/vnd.ms-word.template.macroEnabled.12':
				return 'file-word-o';
				break;
			// Excel
			case 'application/vnd.ms-excel':
			case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
			case 'application/vnd.openxmlformats-officedocument.spreadsheetml.template':
			case 'application/vnd.ms-excel.sheet.macroEnabled.12':
			case 'application/vnd.ms-excel.template.macroEnabled.12':
			case 'application/vnd.ms-excel.addin.macroEnabled.12':
			case 'application/vnd.ms-excel.sheet.binary.macroEnabled.12':
			case 'text/csv':
				return 'file-excel-o';
				break;
			case 'application/json':
			case 'application/javascript':
			case 'application/xhtml+xml':
			case 'application/xml':
			case 'text/xml':
			case 'text/javascript':
			case 'text/html':
			case 'text/cmd':
			case 'text/css':
			case 'text/vcard':
			case 'text/x-markdown':
			case 'text/x-jquery-tmpl':
				return 'file-code-o';
				break;
			// Archive
			case 'application/x-rar-compressed':
			case 'application/x-7z-compressed':
			case 'application/zip':
			case 'application/gzip':
				return 'file-archive-o';
				break;
			// Image
			case 'image/gif':
			case 'image/jpeg':
			case 'image/png':
			case 'image/bmp':
			case 'image/svg+xml':
			case 'image/tiff':
			case 'image/vnd.djvu':
			case 'image/x-xcf':
				return 'file-image-o';
				break;
			// All the others
			default:
				return 'file';
				break;
		}
	}


	/**
	 * Show Alert Deactive Plugin
	 *
	 * @since    1.0.0
	 */
	public function deactive_plugin_alert() {
		$number_file_in_parsaspace = $this->get_count_attachment( 'parsaspace' );
		$helper                    = new Wp_Parsaspace_Helper();
		if ( $number_file_in_parsaspace > 0 ) {
			echo '
         <script type=\'text/javascript\'>
               jQuery(document).ready(function($){
                   jQuery( "a[href*=\'action=deactivate&plugin=wp-parsaspace%2Fwp-parsaspace.php\']" ).click(function(e){
                       e.preventDefault();
                       var  deactive_parsaspace = $(this).attr(\'href\');
                       $.alert({
                           title: \'حذف افزونه\',
                           content: \'کاربر عزیز تعداد <b>' . $helper->per_number( number_format( $number_file_in_parsaspace ) ) . '</b> فایل از وب سایت شما در پارسا اسپیس میزبانی می شود.ابتدا در <a style="color:#ff0000; text-decoration: none;" href="' . admin_url( 'admin.php?page=parsaspace_setting' ) . '">صفحه افزونه</a> اقدام به بازگردانی لینک ها کرده ، سپس افزونه را غیر فعال سازید.<br>\',
                           rtl: true,
                           icon: \'fa fa-lock\',
                           closeIcon: true,
                           buttons: {
                               confirm: {
                                   text: \'برام مهم نیست , افزونه را غیر فعال کن\',
                                   action: function () {
                                       window.location.href = deactive_parsaspace;
                                   }
                               },
                               cancel: {
                                btnClass: \'btn-blue\',
                                   text: \'باشه الان میرم انجام میدم\',
                                   action: function () {
                                   }
                               }
                           }
                       });
                   });
                });
            </script>
        ';
		}
	}


	/**
	 * convert english number to persian number
	 * This function Get From wp-parsidate Plugin Repository (https://github.com/wordpress-parsi/wp-parsidate)
	 *
	 */
	public function per_number( $num, $sp = '٫' ) {
		$eng    = array( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.' );
		$per    = array( '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹', $sp );
		$number = filter_var( $num, FILTER_SANITIZE_NUMBER_INT );

		return empty( $number ) ? str_replace( $per, $eng, $num ) : str_replace( $eng, $per, $num );
	}


	/**
	 * Get Number All Attachment Count
	 *
	 * @since    1.0.0
	 */
	public function get_count_attachment( $type = false, $get_size = false ) {
		global $post;

		$arg                           = array();
		$arg['post_type']              = 'attachment';
		$arg['post_status']            = array( 'any' );
		$arg['posts_per_page']         = '-1';
		$arg['cache_results']          = false;
		$arg['update_post_meta_cache'] = false;
		$arg['update_post_term_cache'] = false;

		//ALL
		if ( $type ) {
			//image
			if ( $type == "image" ) {
				$arg['post_mime_type'] = array( 'image' );
			}
			//audio
			if ( $type == "audio" ) {
				$arg['post_mime_type'] = array( 'audio' );
			}
			//video
			if ( $type == "video" ) {
				$arg['post_mime_type'] = array( 'video' );
			}
			//asnad
			if ( $type == "app" ) {
				$arg['post_mime_type'] = array( 'application', 'text' );
			}
			//me
			if ( $type == "me" ) {
				$arg['author'] = get_current_user_id();
			}
			//all Put to Parsaspace
			if ( $type == "parsaspace" ) {
				$arg['meta_query'][] = array( 'key' => 'cdn_parsaspace', 'value' => '1', 'compare' => '=' );
			}
		}

		$main_query = new \WP_Query( $arg );

		if ( ! $get_size ) {
			return $main_query->post_count;
		} else {
			//get size all
			$size = 0;
			while ( $main_query->have_posts() ):
				$main_query->the_post();
				$size += filesize( get_attached_file( $post->ID ) );
			endwhile;
		}

		wp_reset_postdata();
		return $size;
	}


    /**
     * Search And Replace in Database
     *
     * @param $search_for
     * @param $replace_with
     * @return bool
     * @since    1.0.0
     */
	public function search_and_replace( $search_for, $replace_with ) {
		global $wpdb;

		$table_list = array(
			$wpdb->posts => 'post_content',
			$wpdb->postmeta => 'meta_value',
			//$wpdb->usermeta,
			//$wpdb->options,
        );
		foreach ($table_list as $table_name => $col) {
		    $sql = "UPDATE `{$table_name}` SET `{$col}` = REPLACE({$col}, '{$search_for}', '{$replace_with}')";
		    $wpdb->query($sql);
        }

		//$srdb       = new Wp_Parsaspace_SRDB();
        //		foreach ( $table_list as $tbl ) {
        //			$args = array(
        //				'case_insensitive' => 'off',
        //				'replace_guids'    => 'off',
        //				'dry_run'          => 'off',
        //				'search_for'       => $search_for,
        //				'replace_with'     => $replace_with,
        //				'completed_pages'  => 0,
        //			);
        //			$srdb->srdb( $tbl, $args );
        //		}

		return true;
	}


}

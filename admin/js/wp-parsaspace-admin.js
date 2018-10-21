/* Loader Svg */
function wp_parsaspace_preloader_svg(width = '40', height = '40' , style = 'text-align: center;margin: 15px auto 4px auto;') {
    return `<div style="` + style + `">
  <svg version="1.1" id="loader-modal" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
     width="` + width + `px" height="` + height + `px" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
  <path fill="#f15651" d="M43.935,25.145c0-10.318-8.364-18.683-18.683-18.683c-10.318,0-18.683,8.365-18.683,18.683h4.068c0-8.071,6.543-14.615,14.615-14.615c8.072,0,14.615,6.543,14.615,14.615H43.935z">
    <animateTransform attributeType="xml"
      attributeName="transform"
      type="rotate"
      from="0 25 25"
      to="360 25 25"
      dur="0.6s"
      repeatCount="indefinite"/>
    </path>
  </svg>
</div>`;
}

var file_manager_parsaspace_path_view = '/';

(function( $ ) {
	'use strict';

	/*Set Alert function*/
    function wp_parsaspace_alert(text, type){
        $(".alert_top").remove();
        $('<div class="alert_top alert_' + type + '">' + text + '</div>').appendTo("body").hide().fadeIn('normal');
        setTimeout(function() {$('.alert_top').fadeOut('normal');}, 4200);
    }

	/* Select Quality*/
	$(document).on('change', '#is_optimize', function(){
		let val = $(this).val();
		$("tr#field_quality_jpg").hide();
		if( val ==="no" ) {
            $("tr#field_quality_jpg").show();
		}
	});

	/* Document Ready function */
    $(document).ready(function(){

        /*Set Loading*/
        var loading_confrim_parsaspace = $.dialog({
            title: '',
            lazyOpen: true,
            rtl: true,
            content: '<div class="text-center" id="text_loading_parsaspace">' + wp_parsaspace_preloader_svg() + 'لطفا کمی صبر کنید ...' + '</div>',
            buttons: {},
            closeIcon: false,
            boxWidth: '50%'
        });


        /*Remove Dismiss Notice*/
        $(document).on("click", "i#change_token", function(event){
            event.preventDefault();

            $.dialog({
                title: '',
                rtl: true,
                content: `<div class="text-center">
				<div style="margin: 10px 0px 15px 0px;">شناسه API جدید را وارد نمایید :</div>
				<input type="text" class="form-control" name="new_api_token" style="margin-bottom: 5px;
    width: 80%;
    height: 35px;
    direction: ltr;">
				<button class="btn btn-success" style="margin-top: 5px;
    width: 80%;
    border: 0px;
    height: 35px;
    background: #309a12;
    color: #fff;
    border-radius: 10px; cursor: pointer;" data-function="change_api_token">ثبت اطلاعات</button>
    <div class="text-danger text-center loading_chenge_api" style="display:none; margin-top: 10px;">لطفا کمی صبر کنید ...</div>

				</div>`,
                buttons: {

                },
                closeIcon: true,
            });

        });


        /* change Api Token ParsaSpace */
        $(document).on('click', 'button[data-function=change_api_token]', function(){

            //Show Loading
            $(".loading_chenge_api").show();

            //Ajax Request
            jQuery.ajax({
                url: wp_parsaspace_ajax.ajax_url,
                type: 'get',
                cache: false,
                data: {
                    'action' : 'change_api_token_parsaspace',
                    'security' : wp_parsaspace_ajax.security,
                    'api_token' :  $("input[name=new_api_token]").val(),
                },
                success:function(data) {
                    $(".loading_chenge_api").hide();
                    if(data['error'] =="yes") {
                        wp_parsaspace_alert(data['text'], 'error');
                    } else {
                        window.location.replace(data['redirect']);
                    }
                },
                error: function(){
                    $(".loading_chenge_api").hide();
                    wp_parsaspace_alert('خطا در برقراری ارتباط با بانک اطلاعاتی لطفا دوباره تلاش کنید', 'error');
                }
            });


        });


        /* Test Connection To Parsa Space*/
        $(document).on('click', '#test_connect_parsaspace, button#button_test_connection_parsaspace', function(){

            //Show Loading
            loading_confrim_parsaspace.open();

            //Ajax Request
            jQuery.ajax({
                url: wp_parsaspace_ajax.ajax_url,
                type: 'get',
                cache: false,
                data: {
                    'action' : 'test_connection_to_parsaspace',
                    'security' : wp_parsaspace_ajax.security,
                },
                success:function(data) {
                    loading_confrim_parsaspace.close();
                    if(data['error'] =="yes") {
                        window.location.replace(data['redirect']);
                    } else {
                        wp_parsaspace_alert(data['text'], 'success');
                        $("td#show_alert_connection").html('<span class="text-success"><b>موفق</b></span>');
                        $("button#button_test_connection_parsaspace").hide();
                        $("span.amp").hide();
                    }
                },
                error: function(){
                    loading_confrim_parsaspace.close();
                    wp_parsaspace_alert('خطا در برقراری ارتباط با بانک اطلاعاتی لطفا دوباره تلاش کنید', 'error');
                }
            });


        });

        /* Request Remote File To Parsaspace*/
        $(document).on('click', 'button#remote_file_from_url', function(){

            //Show Loading
            loading_confrim_parsaspace.open();

            //Ajax Request
            jQuery.ajax({
                url: wp_parsaspace_ajax.ajax_url,
                type: 'get',
                cache: false,
                data: {
                    'action' : 'request_remote_url_to_parsaspace',
                    'security' : wp_parsaspace_ajax.security,
                    'file_url' : $("input[name=remote_upload_url]").val(),
                    'file_name' : $("input[name=remote_upload_file_name]").val(),
                },
                success:function(data) {
                    loading_confrim_parsaspace.close();
                    if(data['error'] =="yes") {
                        wp_parsaspace_alert(data['text'], 'error');
                    } else {
                        window.location.replace(data['redirect']);
                    }
                },
                error: function(){
                    loading_confrim_parsaspace.close();
                    wp_parsaspace_alert('خطا در برقراری ارتباط با بانک اطلاعاتی لطفا دوباره تلاش کنید', 'error');
                }
            });


        });

        /* Transfer To ParsaSpace*/
        $(document).on('click', 'div[data-upload-file] button', function(){

            //file Id
            let post_id = $(this).attr('data-file');

            //Show Loading
            loading_confrim_parsaspace.open();

            //check text Loading
            let load_text = jQuery("#text_loading_parsaspace");
            setTimeout(function(){
                load_text.html(wp_parsaspace_preloader_svg() + "در حال انتقال فایل به پارسا اسپیس ...");
            }, 6000);
            setTimeout(function(){
                load_text.html(wp_parsaspace_preloader_svg() + "در حال تغییر آدرس فایل در پایگاه داده ...");
            }, 15000);

            //Ajax Request
            jQuery.ajax({
                url: wp_parsaspace_ajax.ajax_url,
                type: 'get',
                cache: false,
                data: {
                    'action' : 'transfer_to_parsaspace',
                    'security' : wp_parsaspace_ajax.security,
                    'post_id' : post_id,
                },
                success:function(data) {
                    loading_confrim_parsaspace.close();
                    if(data['error'] =="yes") {
                        wp_parsaspace_alert(data['text'], 'error');
                    } else {
                        wp_parsaspace_alert("فایل با موفقیت به پارسااسپیس منتقل شد", 'success');
                        $("div[data-upload-file=" + post_id + "]").html("پارسا اسپیس");
                    }
                },
                error: function(){
                    loading_confrim_parsaspace.close();
                    wp_parsaspace_alert('خطا در برقراری ارتباط با بانک اطلاعاتی لطفا دوباره تلاش کنید', 'error');
                }
            });


        });


        /* Change Setting Domain */
        $(document).on('click', '#change_domain_setting', function(){

            //Show Loading
            loading_confrim_parsaspace.open();

            //Ajax Request
            jQuery.ajax({
                url: wp_parsaspace_ajax.ajax_url,
                type: 'get',
                cache: false,
                data: {
                    'action' : 'change_setting_domain_parsaspace',
                    'security' : wp_parsaspace_ajax.security,
                    'domain_name' : $("input#domain_name").val(),
                    'is_ssl' : $("select#is_ssl").val(),
                    'base_folder' : $("input#base_folder").val(),
                },
                success:function(data) {

                    if(data['error'] =="yes") {
                        loading_confrim_parsaspace.close();
                        wp_parsaspace_alert(data['text'], 'error');
                    } else {

                        var redirect = data['redirect'];
                        setTimeout(function(){
                            loading_confrim_parsaspace.close();
                            window.location.replace(redirect);
                        }, 5000);

                    }
                },
                error: function(){
                    loading_confrim_parsaspace.close();
                    wp_parsaspace_alert('خطا در برقراری ارتباط با بانک اطلاعاتی لطفا دوباره تلاش کنید', 'error');
                }
            });

        });


        /* Remove Plugin Parsaspace Query*/
        $(document).on('click', '#reset_all_url_plugin_parsaspace', function(){


            var  redirect_url = $(this).attr('data-href');
            $.alert({
                title: 'حذف افزونه',
            content: 'در صورت تایید حذف افزونه ،  تمامی آدرس های رسانه های وردپرس به حالت پیش فرض بر می گردند <br> پس از انجام این عملیات شما میتوانید مطابق با راهنمایی <a href="https://realwp.net/wp-parsaspace" target="_blank" class="text-danger no-dec">این صفحه</a> تمامی فایل های وردپرس خود را از پارسا اسپیس به هاست فعلی منتقل کنید.',
            rtl: true,
             icon: 'fa fa-trash',
            closeIcon: true,
                buttons: {
                confirm: {
                    text: 'بله میدانم عملیات را شروع کن',
                    action: function () {

                        //Show Loading
                        loading_confrim_parsaspace.open();

                        //Ajax Request
                        jQuery.ajax({
                            url: wp_parsaspace_ajax.ajax_url,
                            type: 'get',
                            cache: false,
                            data: {
                                'action' : 'reset_all_url_in_db_parsaspace',
                                'security' : wp_parsaspace_ajax.security,
                            },
                            success:function(data) {

                                if(data['error'] =="yes") {
                                    loading_confrim_parsaspace.close();
                                    wp_parsaspace_alert(data['text'], 'error');
                                } else {

                                    setTimeout(function(){
                                        loading_confrim_parsaspace.close();
                                        window.location.replace(redirect_url);
                                    }, 9000);

                                }
                            },
                            error: function(){
                                loading_confrim_parsaspace.close();
                                wp_parsaspace_alert('خطا در برقراری ارتباط با بانک اطلاعاتی لطفا دوباره تلاش کنید', 'error');
                            }
                        });

                    }
                },
                cancel: {
                    btnClass: 'btn-blue',
                    text: 'خیر',
                    action: function () {
                    }
                }
            }
        });

        });


        /* Ajax Request Change Setting Parsaspace*/
        $(document).on('click', '#change_parsaspace_setting_form', function(){

            //Show Loading
            loading_confrim_parsaspace.open();

            //Ajax Request
            jQuery.ajax({
                url: wp_parsaspace_ajax.ajax_url,
                type: 'get',
                cache: false,
                data: {
                    'action' : 'change_parsaspace_setting_form',
                    'security' : wp_parsaspace_ajax.security,
                    'is_optimize' : $("select#is_optimize").val(),
                    'quality_jpg' : $("input#quality_jpg").val(),
                    'max_size_upload' : $("input#max_size_upload").val(),
                    'is_automatic_upload' : $("select#is_automatic_upload").val(),
                    'remote_dir' : $("input#remote_dir").val(),
                },
                success:function(data) {
                    loading_confrim_parsaspace.close();
                    if(data['error'] =="yes") {
                        wp_parsaspace_alert(data['text'], 'error');
                    } else {
                        wp_parsaspace_alert("تنظیمات با موفقیت بروز رسانی شد", 'success');
                    }
                },
                error: function(){
                    loading_confrim_parsaspace.close();
                    wp_parsaspace_alert('خطا در برقراری ارتباط با بانک اطلاعاتی لطفا دوباره تلاش کنید', 'error');
                }
            });

        });


        /* ajax Request install Step*/
        $(document).on('click', '#install_parsaspace_submit', function(){

            //Show Loading
            loading_confrim_parsaspace.open();

            //Ajax Request
            jQuery.ajax({
                url: wp_parsaspace_ajax.ajax_url,
                type: 'get',
                cache: false,
                data: {
                    'action' : 'install_parsaspace',
                    'security' : wp_parsaspace_ajax.security,
                    'token_api' : $("input#token_api").val(),
                    'domain_name' : $("input#domain_name").val(),
                    'is_ssl' : $("select#is_ssl").val(),
                    'base_folder' : $("input#base_folder").val(),
                    'is_optimize' : $("select#is_optimize").val(),
                    'quality_jpg' : $("input#quality_jpg").val(),
                    'max_size_upload' : $("input#max_size_upload").val(),
                    'is_automatic_upload' : $("select#is_automatic_upload").val(),
                },
                success:function(data) {
                    loading_confrim_parsaspace.close();
                    if(data['error'] =="yes") {
                        wp_parsaspace_alert(data['text'], 'error');
                    } else {
                        wp_parsaspace_alert("تبریک :) وب سایت شما با موفقیت به پارسا اسپیس متصل شد", 'success');
                        window.location.replace(data['redirect']);
                    }
                },
                error: function(){
                    loading_confrim_parsaspace.close();
                    wp_parsaspace_alert('خطا در برقراری ارتباط با بانک اطلاعاتی لطفا دوباره تلاش کنید', 'error');
                }
            });

        });

        /*Again Load File Manager*/
        $(document).on('click', '#again_load_file_manager', function(event) {
            event.preventDefault();
           load_content_file_manager_wp_parsaspace();
        });

        /*Show Alert Link*/
        $(document).on('click', 'span[data-alert-url-parsaspace]', function(event) {
            event.preventDefault();
            alert($(this).attr("data-alert-url-parsaspace"));
        });


        /* Show File Manager function Load*/
        function load_content_file_manager_wp_parsaspace() {

            //Show Loading
            $(".parsaspace_content_file_manager").html('<div style="margin-top:180px;"><div class="text-center">' + wp_parsaspace_preloader_svg() + 'لطفا کمی صبر کنید ...' + '</div></div>');


            //Ajax Request
            jQuery.ajax({
                 url: wp_parsaspace_ajax.ajax_url,
                 type: 'get',
                 cache: false,
                 data: {
                     'action' : 'wp_parsaspace_filemanager_api',
                     'security' : wp_parsaspace_ajax.security,
                     'step' : 'file_manager',
                     'path' : file_manager_parsaspace_path_view,
                 },
                 success:function(data) {

                     if(data['error'] =="yes") {
                         $(".parsaspace_content_file_manager").html('<div style="margin-top:180px;"><div class="text-center" id="again_load_file_manager" style="cursor: pointer;"><i class="fa fa-exclamation-triangle text-warning" style="display: block;font-size: 70px;margin-bottom: 10px;"></i> ارتباط با پارسا اسپیس برقرار نشد<br>  برای تلاش دوباره اینجا کلیک کنید.</div></div>');
                     } else {

                         $(".parsaspace_content_file_manager").html(data['text']);
                         file_manager_parsaspace_path_view = data['path'];
                     }

                 },
                 error: function(){
                     $(".parsaspace_content_file_manager").html('<div style="margin-top:180px;"><div class="text-center" id="again_load_file_manager" style="cursor: pointer;"><i class="fa fa-exclamation-triangle text-warning" style="display: block;font-size: 70px;margin-bottom: 10px;"></i> ارتباط با پارسا اسپیس برقرار نشد<br>  برای تلاش دوباره اینجا کلیک کنید.</div></div>');
                 }
             });




        }


        /* Go To Folder ParsaSpace file Manager */
        $(document).on('click', 'span[data-show-parsaspace-path]', function(event) {
            event.preventDefault();

            file_manager_parsaspace_path_view = $(this).attr("data-show-parsaspace-path");

            //Show file Manager
            load_content_file_manager_wp_parsaspace();
        });


        /* Show file Manager Parsaspace */
        $(document).on('click', 'a.show_wp_parsaspace_file_manager', function(event){
            event.preventDefault();

            //Show File Manager
            $("#file_manager_parsaspace").hide().show();

            //Show file Manager
            load_content_file_manager_wp_parsaspace();

        });


        /* Close Button file Manager */
        $(document).on('click', '#file_manager_parsaspace .close_box', function(event){
            event.preventDefault();

            //reset Path
            file_manager_parsaspace_path_view = "/";

            //Remove All Content
            $(".parsaspace_content_file_manager").html("");

            //Show File Manager
            $("#file_manager_parsaspace").hide();

        });


        /* Remove File And folder Request */
        /* Transfer To ParsaSpace*/
        $(document).on('click', 'span[data-delete-parsaspace]', function(){

            //file Id
            let path_remove = $(this).attr('data-delete-parsaspace');

            //Show Alert Seciruty
            $.alert({
                title: 'تایید حذف',
            content: 'آیا مطمئن به حذف موارد انتخاب شده هستید ؟',
            rtl: true,
                icon: 'fa fa-trash',
            closeIcon: true,
                buttons: {
                confirm: {
                    text: 'حذف کن !',
                    btnClass: 'btn-blue',
                    action: function () {

                        //Show Loading
                        loading_confrim_parsaspace.open();

                        //Ajax Request
                        jQuery.ajax({
                            url: wp_parsaspace_ajax.ajax_url,
                            type: 'get',
                            cache: false,
                            data: {
                                'action' : 'wp_remove_path_parsaspace',
                                'security' : wp_parsaspace_ajax.security,
                                'path_remove' : path_remove,
                            },
                            success:function(data) {
                                loading_confrim_parsaspace.close();
                                if(data['error'] =="yes") {
                                    wp_parsaspace_alert(data['text'], 'error');
                                } else {
                                    wp_parsaspace_alert("عملیات حذف با موفقیت انجام شد", 'success');
                                    load_content_file_manager_wp_parsaspace();
                                }
                            },
                            error: function(){
                                loading_confrim_parsaspace.close();
                                wp_parsaspace_alert('خطا در برقراری ارتباط با بانک اطلاعاتی لطفا دوباره تلاش کنید', 'error');
                            }
                        });

                    }
                },
                cancel: {
                    text: 'منصرف شدم',
                    action: function () {
                    }
                }
            }
        });

        });




    });






})( jQuery );

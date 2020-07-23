<?php

class Wp_Parsaspace_Admin_Ui {

    /**
     * Meta Box Show Static Media
     *
     * @since    1.0.0
     */
    public static function meta_box_static_media()
    {

      $helper = new Wp_Parsaspace_Helper();
      $list = array(
            array("title" => "عکس", "icon" => "fa-camera", "count" => number_format($helper->get_count_attachment("image")), "color" => "#1ab394 " ),
            array("title" => "صوت", "icon" => "fa-microphone", "count" => number_format($helper->get_count_attachment("audio")), "color" => "#23c6c8"),
            array("title" => "ویدئو", "icon" => "fa-video-camera", "count" => number_format($helper->get_count_attachment("video")), "color" => "#1c84c6"),
            array("title" => "اسناد", "icon" => "fa-file-text-o", "count" => number_format($helper->get_count_attachment("app")), "color" => "#f8ac59"),
            array("title" => "مجموع", "icon" => "fa-save", "count" => number_format($helper->get_count_attachment()), "color" => "", ),
        );
      $ul_text = '';
      $title = '';
      $count = '';
      for ($i=0;$i<5;$i++) {
            $ul_text .= '
            <li>
            <span class="pull-left"> 
            '.($list[$i]["count"] ==0 ? '-' :  $helper->per_number( $list[$i]['count'] ). ' فایل' ).'</span>
            <i class="fa '.$list[$i]["icon"].'"></i>
            '.$list[$i]['title'].'
            </li>';
            if ( $i !=4 ) {
                $title .= "'".$list[$i]['title']."'";
                $count .= $list[$i]['count'];
            }
            if ( $i !=3 ) { $title .=','; $count .=','; }
        }
        ?>
        <canvas id="myChart" height="100vh" width="90vw"></canvas>
        <script>
            var ctx = document.getElementById('myChart').getContext('2d');
            var chart = new Chart(ctx, {
                type: 'pie',
                data: {
                    datasets: [{
                        data: [<?php echo $count; ?>],
                        backgroundColor: [
                            '#36A2EB',
                            '#54a85a',
                            '#ff9a36',
                            '#ff1a67',
                            '#40bde6',
                        ]
                    }],
                    labels: [<?php echo $title; ?>]
                },
                // Configuration options go here
                options: {
                    layout: {
                        padding: {
                            left: 0,
                            right: 0,
                            top: 0,
                            bottom: 0
                        }
                    },
                    legend: {
                        display: false,
                    }
                }
            });
        </script>
        <ul class="list_static">
        <?php
        echo $ul_text;
        echo '</ul>';
    }


    /**
     * Meta Box Show Cluod Media
     *
     * @since    1.0.0
     */
    public static function meta_box_static_media_cluod()
    {
        $helper = new Wp_Parsaspace_Helper();
        $number_all = $helper->get_count_attachment();
        $in_parsaspace = $helper->get_count_attachment('parsaspace');
        $in_host = $number_all - $in_parsaspace;
        $parspace_percentege = @round(($in_parsaspace / $number_all) * 100 );
        $inhost_percentege = @round(100 - $parspace_percentege );

        ?>
        <canvas id="myChart_doughnut" height="100vh" width="90vw"></canvas>
        <script>
            var ctx_doughnut = document.getElementById('myChart_doughnut').getContext('2d');
            var chart_doughnut = new Chart(ctx_doughnut, {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [<?php echo $in_parsaspace.','. $in_host; ?>],
                        backgroundColor: [
                            '#54a85a',
                            '#ff9a36',
                        ]
                    }],
                    labels: ['پارسا اسپیس', 'هاست وب سایت']
                },

                // Configuration options go here
                options: {
                    layout: {
                        padding: {
                            left: 0,
                            right: 0,
                            top: 0,
                            bottom: 0
                        }
                    },
                    legend: {
                        display: false,
                    }
                }
            });
        </script>
        <ul class="list_static m-t-10">
        <?php
        echo '<li><span class="pull-left"> '.($in_parsaspace ==0 ? '-' :  $helper->per_number( number_format ( $in_parsaspace ) ). ' فایل'.'<span class="percent text-danger">('. $helper->per_number($parspace_percentege ) .'%)</span>' ).'</span>پارسا اسپیس</li>';
        echo '<li><span class="pull-left"> '.($in_host ==0 ? '-' :  $helper->per_number( number_format ( $in_host ) ). ' فایل'.'<span class="percent text-danger">('. $helper->per_number($inhost_percentege ) .'%)</span>' ).'</span>هاست وب سایت</li>';
        echo '</ul>';

        if ($in_host >0) {
            echo '<div class="text-center m-t-30"><a href="'.admin_url('upload.php?mode=list').'" target="_blank" class="text-primary no-dec">انتقال فایل ها به پارسا اسپیس</a></div>';
        }

    }


    /**
     * Meta Box Show Developer About
     *
     * @since    1.0.0
     */
    public static function meta_box_developer()
    {
$helper = new Wp_Parsaspace_Helper();
echo '
<div class="text-center m-t-10">
<a href="https://realwp.net/" target="_blank" title="وردپرس واقعی"><img class="realwp_logo" src="'.WP_PARSASPACE_DIR_URL.'/admin/images/logo-realwp.png" alt="وردپرس واقعی"></a>
</div>
<ul class="list_developer m-t-10">
<li>نام افزونه : <span class="pull-left">Wp-Parsaspace</span></li>
<li>نسخه : <span class="pull-left">'.WP_PARSASPACE_VERSION.'</span></li>
<li>برنامه نویس : <span class="pull-left"><a href="https://realwp.net/" target="_blank" class="no-dec" title="توسعه دهنده وردپرس">مهرشاد درزی</a></span></li>
<li><a href="https://realwp.net/wp-parsaspace" target="_blank" class="text-danger no-dec"><i class="fa fa-question-circle" style="vertical-align: -2px;margin-left: 2px;"></i> راهنمای جامع افزونه</a></li>
</ul>';
}


    /**
     * Meta Box Show review Plugin
     *
     * @since    1.0.0
     */
    public static function meta_box_review()
    {
        $helper = new Wp_Parsaspace_Helper();

        /* test Connection */
        $api = new Wp_Parsaspace_Api();
        $test_con = $api->TestApi();
        if( $test_con ===false ) {
           $test_connect = '<b>عدم اتصال</b> &nbsp; <span class="text-danger mouse-pointer" id="test_connect_parsaspace"><b>(دوباره بررسی کن)</b></span>';
        } else {
           $test_connect = '<span class="text-success"><b>موفق</b></span>';
        }

        $opt = get_option('wp_parsaspace_opt');


        echo '
<table class="form-table m-sub-t-10">
                <tbody>
                <tr>
                    <th><label for="token_api">وضعیت اتصال با پارسا اسپیس</label></th>
                    <td id="show_alert_connection">'.$test_connect.'</td>
                </tr>

                <tr>
                    <th><label for="token_api">API Token</label></th>
                    <td><input type="text" name="token_api" id="token_api" value="'.$opt['api_token'].'" autocomplete="off" class="regular-text text-left ltr" '.( isset($_GET['change_inf']) ===true ? '' : 'readonly').'>
                    <!--<span><i class="fa fa-pencil i-pen" id="change_token"></i></span>-->
                    </td>
                </tr>

<tr>
<th><label for="domain_name">نام دامنه</label></th>
<td>';

if ( isset($_GET['change_inf']) ) {
    echo '
     <input type="text" name="domain_name" id="domain_name" value="'.$opt['domain_name'].'" autocomplete="off" class="regular-text text-left ltr">
    <span class="description">نام دامنه ثبت شده در پارسا اسپیس بدون http وارد نمایید مثلا test.parsaspace.com</span>
            ';
} else {
           echo  '<span class="text-danger" style="font-size: 18px;">'.$opt['domain_name'].'</span>';
}


echo '
</td>
</tr>

<tr>
<th><label for="is_ssl">آیا دامنه متصل به SSL هست ؟</label></th>
<td>';

if ( isset($_GET['change_inf']) ) {
    echo '
     <select name="is_ssl" id="is_ssl" class="h35">
    <option value="no" '.selected( $opt['is_ssl'], "no", false ).'>خیر</option>
    <option value="yes" '.selected( $opt['is_ssl'], "yes", false ).'>بله</option>
</select>
<span class="description">در صورتی که دامنه متصل به SSL و با https وارد می شود اعلام کنید</span>
            ';
} else {
           echo  ($opt['is_ssl'] =="yes" ? "آری" : "خیر");
}

echo '
</td>
</tr>
<tr>
<th><label for="base_folder">پوشه قرارگیری فایل ها</label></th>
<td>';

if ( isset($_GET['change_inf']) ) {

echo '
<input type="text" name="base_folder" id="base_folder" autocomplete="off" value="'.$opt['base_folder'].'" value="file" class="regular-text text-left ltr">
<span class="description">در صورتی که میخواهید فایل ها در یک پوشه ی اختصاصی در پارسا اسپیس آپلود شوند آن را وارد کنید مثلا : wp</span>
';

} else {
    echo '<span style="font-size: 18px;">'.$opt['base_folder'].'</span>';
}

echo '
</td>
</tr>
</tbody>
</table>
';

echo '<div class="text-center" style="margin: 35px auto 20px auto;">';

//Change Setting
if ( isset($_GET['change_inf']) ) {
    echo '
<button type="button" class="button button-primary" id="change_domain_setting" />ذخیره اطلاعات</button>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<button type="button" class="button button-secondary" onclick="window.location.replace(\''.admin_url("admin.php?page=parsaspace_setting").'\');" />منصرف شدم !</button>

';
} else {

 echo '
<button type="button" class="button button-primary" onclick="window.location.replace(\''.admin_url("admin.php?page=parsaspace_setting&change_inf=yes").'\');" />تغییر اطلاعات پایه</button>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<button type="button" class="button button-secondary" id="button_test_connection_parsaspace" />بررسی اتصال سایت با پارسا اسپیس</button>
<span class="amp">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';

$number_file_in_parsaspace = $helper->get_count_attachment('parsaspace');
if($number_file_in_parsaspace >0) {
echo '
<button type="button" class="button button-secondary delete" data-href="'.admin_url("admin.php?page=parsaspace_setting&remove_plugin_setting=yes").'" id="reset_all_url_plugin_parsaspace" />بازگردانی تمامی اطلاعات و حذف افزونه</button>
';
}

}

echo '</div>';

}



    /**
     * Meta Box Show System Setting
     *
     * @since    1.0.0
     */
    public static function meta_box_system()
    {
        $helper = new Wp_Parsaspace_Helper();

        $opt = get_option('wp_parsaspace_opt');

echo '
<table class="form-table m-sub-t-10">
<tbody>

<tr style="display: none;">
<th><label for="is_automatic_upload">آپلود اتوماتیک فایل ها</label></th>
<td>
<select name="is_automatic_upload" id="is_automatic_upload" class="h35">
<!--
<option value="yes" '.selected( $opt['is_automatic_upload'], "yes", false ).'>بله</option>
<option value="no" '.selected( $opt['is_automatic_upload'], "no", false ).'>خیر</option>
-->
<option value="no">خیر</option>
</select>
<span class="description">در صورتی که میخواهید بعد از آپلود فایل جدید به صورت اتوماتیک به پارسا اسپیس منتقل شود گزینه بله, در غیر این صورت اگر میخواهید فایل ها به انتخاب شما در کتابخانه وردپرس آپلود شوند گزینه ی خیر را انتخاب کنید</span>
</td>
</tr>

<tr>
<th><label for="remote_dir">پوشه دانلود فایل (Remote Upload)</label></th>
<td>
<input type="text" name="remote_dir" id="remote_dir" value="'.$opt['remote_dir'].'" autocomplete="off" class="regular-text text-left ltr">
<span class="description">مشخص کنید فایل هایی که از طریق آدرس مستقیم ، دانلود و در پارسا اسپیس ارسال می شود در کدام پوشه قرار گیرد مثلا : direct</span>
</td>
</tr>


<tr>
<th><label for="is_optimize">من افزونه بهینه ساز دارم</label></th>
<td>
<select name="is_optimize" id="is_optimize" class="h35">
<option value="no" '.selected( $opt['is_optimize'], "no", false ).'>خیر</option>
<option value="yes" '.selected( $opt['is_optimize'], "yes", false ).'>بله</option>
</select>
<span class="description">در صورتی که از افزونه های بهینه سازی تصاویر مانند wp smush image و ... استفاده می کنید. این گزینه را انتخاب کنید تا تصاویر بعد از بهینه سازی در پاسااسپیس بارگزاری شوند</span>
</td>
</tr>

<tr id="field_quality_jpg"'.($opt['is_optimize'] =="yes" ? " style='display: none;'" : "").'>
<th><label for="quality_jpg">کیفیت تصاویر</label></th>
<td>
<input type="text" name="quality_jpg" id="quality_jpg" value="'.$opt['quality_jpg'].'" autocomplete="off" value="90" class="regular-text text-left ltr">
<span class="description">یک عدد از بین 0 تا 100 برای کیفیت عکس هایی که در وردپرس آپلود می شود وارد نمایید .بهترین گزینه بین 80 تا 95 می باشد</span>
</td>
</tr>

<tr>
<th><label for="max_size_upload">حداکثر حجم آپلود فایل</label></th>
<td>
<input type="text" name="max_size_upload" id="max_size_upload" value="'.$opt['max_size_upload'].'" autocomplete="off" value="50" class="regular-text text-left ltr">
<span class="description">حداکثر حجم فایل قابل آپلود را به مگابایت وارد نمایید مثلا : 50</span>
</td>
</tr>

</tbody>
</table>
';


echo '<div class="text-center" style="margin: 35px auto 20px auto;">
<button type="button" class="button button-secondary" id="change_parsaspace_setting_form" />تغییر تنظیمات</button>
</div>';

    }



    /**
     * Meta Box Remote Upload
     *
     * @since    1.0.0
     */
    public static function meta_box_remote_upload()
    {

echo '
<table class="form-table m-sub-t-10">
<tbody>

<tr>
<th><label for="remote_upload_url">آدرس لینک مستقیم فایل</label></th>
<td>
<input type="text" style="width: 100%;" name="remote_upload_url" id="remote_upload_url" placeholder="https://cdn.realwp.net/wordpress-fa.zip" autocomplete="off" class="regular-text text-left ltr">
<span class="description">لینک مستقیم فایل را در این قسمت وارد کنید ، پارسااسپیس آن را دانلود می کند و آن را در فایل منجر شما قرار می دهد</span>
</td>
</tr>

<tr>
<th><label for="remote_upload_file_name">نام فایل</label></th>
<td>
<input type="text" name="remote_upload_file_name" id="remote_upload_file_name" placeholder="my-file-name" autocomplete="off" class="regular-text text-left ltr">
<span class="description">تنها در صورتی که می خواهید نام فایل تغییر کند ، این قسمت را پر کنید</span>
</td>
</tr>

</tbody>
</table>
';

echo '<div class="text-center" style="margin: 35px auto 20px auto;">
<button type="button" class="button button-primary" id="remote_file_from_url" />فایل را دانلود کن</button>
</div>';

echo '<div class="text-left">
<a href="#" class="show_wp_parsaspace_file_manager"><i class="fa fa-folder-open-o" style="padding-left: 5px;vertical-align: -2px;"></i> مدیریت فایل ها</a>
</div>
<div class="clearfix"></div>';


echo '<div id="file_manager_parsaspace" class="install_step_parsaspace">
<div class="close_box text-left"><i class="fa fa-close"></i></div>
<div class="clearfix"></div>
<div class="parsaspace_content_file_manager text-right">
</div>
</div>';




    }


    /**
     * Show Setting Page Admin
     *
     * @since    1.0.0
     */
    public static function setting_page()
    {
        global $screen_layout_columns, $hook_suffix;

        /* enable add_meta_boxes function in this page. */
        do_action( 'add_meta_boxes_parsaspace', $hook_suffix );
        $pagehook = 'toplevel_page_parsaspace_setting';
        ?>
        <div id="admin_page_wp_parsaspace" class="wrap admin_wrap">
            <div class="center-logo">
                <a href="http://parsaspace.com/" target="_blank"><img src="<?php echo WP_PARSASPACE_DIR_URL; ?>/admin/images/logo-parsaspace.png" alt="ParsaSpace"></a>
            </div>
        <h2 class="d-none">&nbsp;</h2>


            <form id="wp-parsaspace_metaboxes-general" class="wrap">
                <form action="admin-post.php" method="post">
                    <?php wp_nonce_field('wp-parsaspace_metaboxes-general'); ?>
                    <?php wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false ); ?>
                    <?php wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false ); ?>
                    <input type="hidden" name="action" value="save_wp_parsaspace_metaboxes_general" />
                    <div id="poststuff">
                        <div id="post-body" class="metabox-holder columns-<?php echo 1 == get_current_screen()->get_columns() ? '1' : '2'; ?>">
                            <div id="postbox-container-1" class="postbox-container">
                                <?php do_meta_boxes($pagehook, 'side', ''); ?>
                            </div><!-- #postbox-container-1 -->
                            <div id="postbox-container-2" class="postbox-container">
                                <?php do_meta_boxes($pagehook, 'normal', ''); ?>
                            </div><!-- #postbox-container-2 -->
                        </div><!-- #post-body -->
                        <br class="clear">
                    </div><!-- #poststuff -->
                </form>
        </div>
        <script type="text/javascript">
            //<![CDATA[
            jQuery(document).ready( function($) {
                // close postboxes that should be closed
                $('.if-js-closed').removeClass('if-js-closed').addClass('closed');
                // postboxes setup
                postboxes.add_postbox_toggles('<?php echo $pagehook; ?>');
            });
            //]]>
        </script>

        <?php
    }
    

    /**
     * Show Install Step
     *
     * @since    1.0.0
     */
    public function install_step() {
        ?>
        <div class="install_step_parsaspace text-center">
            <div class="logo_parsaspace"><img src="<?php echo WP_PARSASPACE_DIR_URL; ?>/admin/images/logo-parsaspace.png" alt="ParsaSpace"></div>

            به مراحل نصب پارسا اسپیس خوش آمدید :)
            <br>
            <a href="https://realwp.net/wp-parsaspace" traget="_blank">[ راهنمای کامل نصب سرویس ]</a>
            <br><br>
            <div class="table_install_step text-right">

            <table class="form-table">
                <tbody>

                <tr>
                    <th><label for="token_api">API Token</label></th>
                    <td><input type="text" name="token_api" id="token_api" value="" autocomplete="off" class="regular-text text-left ltr">
                    </td>
                </tr>

                <tr>
                    <th><label for="domain_name">نام دامنه</label></th>
                    <td>
                        <input type="text" name="domain_name" id="domain_name" value="" autocomplete="off" class="regular-text text-left ltr">
                        <span class="description">نام دامنه ثبت شده در پارسا اسپیس بدون http وارد نمایید مثلا test.parsaspace.com</span>
                    </td>
                </tr>

                <tr>
                    <th><label for="is_ssl">آیا دامنه متصل به SSL هست ؟</label></th>
                    <td>
                        <select name="is_ssl" id="is_ssl" class="h35">
                            <option value="no">خیر</option>
                            <option value="yes">بله</option>
                        </select>
                        <span class="description">در صورتی که دامنه متصل به SSL و با https وارد می شود اعلام کنید</span>
                    </td>
                </tr>

                <tr>
                    <th><label for="base_folder">پوشه قرارگیری فایل ها</label></th>
                    <td>
                        <input type="text" name="base_folder" id="base_folder" autocomplete="off" value="file" class="regular-text text-left ltr">
                        <span class="description">در صورتی که میخواهید فایل ها در یک پوشه ی اختصاصی در پارسا اسپیس آپلود شوند آن را وارد کنید مثلا : wp</span>
                    </td>
                </tr>

                <tr style="display: none;">
                    <th><label for="is_automatic_upload">آپلود اتوماتیک فایل ها</label></th>
                    <td>
                        <select name="is_automatic_upload" id="is_automatic_upload" class="h35">
                        <!--
                            <option value="yes">بله</option>
                            <option value="no">خیر</option>
                            -->
                            <option value="no">خیر</option>
                        </select>
                        <span class="description">در صورتی که میخواهید بعد از آپلود فایل جدید به صورت اتوماتیک به پارسا اسپیس منتقل شود گزینه بله, در غیر این صورت اگر میخواهید فایل ها به انتخاب شما در کتابخانه وردپرس آپلود شوند گزینه ی خیر را انتخاب کنید</span>
                    </td>
                </tr>

                <tr>
                    <th><label for="is_optimize">من افزونه بهینه ساز دارم</label></th>
                    <td>
                        <select name="is_optimize" id="is_optimize" class="h35">
                            <option value="no">خیر</option>
                            <option value="yes">بله</option>
                        </select>
                        <span class="description">در صورتی که از افزونه های بهینه سازی تصاویر مانند wp smush image و ... استفاده می کنید. این گزینه را انتخاب کنید تا تصاویر بعد از بهینه سازی در پاسااسپیس بارگزاری شوند</span>
                    </td>
                </tr>

                <tr id="field_quality_jpg">
                    <th><label for="quality_jpg">کیفیت تصاویر</label></th>
                    <td>
                        <input type="text" name="quality_jpg" id="quality_jpg" autocomplete="off" value="90" class="regular-text text-left ltr">
                        <span class="description">یک عدد از بین 0 تا 100 برای کیفیت عکس هایی که در وردپرس آپلود می شود وارد نمایید .بهترین گزینه بین 80 تا 95 می باشد</span>
                    </td>
                </tr>

                <tr>
                    <th><label for="max_size_upload">حداکثر حجم آپلود فایل</label></th>
                    <td>
                        <input type="text" name="max_size_upload" id="max_size_upload" autocomplete="off" value="50" class="regular-text text-left ltr">
                        <span class="description">حداکثر حجم فایل قابل آپلود را به مگابایت وارد نمایید مثلا : 50</span>
                    </td>
                </tr>

                </tbody>
            </table>
        </div>

         <div class="submit_btn text-center">
             <input type="submit" name="submit" id="install_parsaspace_submit" class="button button-primary" autocomplete="off" value="ذخیره و نصب پارسا اسپیس"  />
             <a href="<?php echo admin_url('index.php'); ?>?disable_install_parsaspace=yes">بزار برای بعدا !</a>
             <br>
             <p class="copyright">
             <a href="https://realwp.net" title="wordpress developer" target="_blank">برنامه نویس و توسعه : مهرشاد درزی </a>
             </p>
         </div>

        <br><br><br><br>
        </div>
        <?php
    }



}

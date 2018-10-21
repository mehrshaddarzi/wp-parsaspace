<?php

class Wp_Parsaspace_Api {

    public $token;
    public $domain;
    public $api;

    public function __construct() {

        $opt = get_option('wp_parsaspace_opt');
        $this->token =  $opt['api_token'];
        $this->domain =  $opt['domain_name'];
        $this->api =  'http://api.parsaspace.com/v1';

    }


    /**
     * Remote Upload Service
     *
     * @since    1.0.0
     */
    public function RemoteUpload( $path, $url ) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api."/remote/new",
            CURLOPT_RETURNTRANSFER=>true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "checkid=".time()."&path=".$path."&url=".$url."&domain=".$this->domain,
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer ".$this->token,
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return false;
        } else {
            return true;
        }
    }


    /**
     * Remove File From ParsaSpace
     *
     * @since    1.0.0
     */
    public function RemoveFile( $path ) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api."/files/remove",
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_RETURNTRANSFER=>true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "path=".$path."&domain=".$this->domain."&type=json",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer ".$this->token."",
                "cache-control: no-cache",
                "content-type: application/x-www-form-urlencoded",
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return false;
        } else {
            return true;
        }
    }


    /**
     * Create folder in Host
     *
     * @since    1.0.0
     */
    public function CreateFolder( $path ) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api."/files/createfolder",
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_RETURNTRANSFER=>true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "path=".$path."&domain=".$this->domain."&type=json",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer ".$this->token."",
                "cache-control: no-cache",
                "content-type: application/x-www-form-urlencoded",
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return false;
        } else {
            return true;
        }
    }


    /**
     * Rename File Or Folder in ParsaSpace
     *
     * @since    1.0.0
     */
    public function Rename( $path, $rename_to_path ) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api."/files/rename",
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_RETURNTRANSFER=>true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "source=".$path."&destination=".$rename_to_path."&domain=".$this->domain."&type=json",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer ".$this->token."",
                "cache-control: no-cache",
                "content-type: application/x-www-form-urlencoded",
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Test Connect To Api Service With file list
     *
     * @since    1.0.0
     */
    public function TestApi($token = false, $domain = false ) {

        if( $token ===false ) $token = $this->token;
        if( $domain ===false ) $domain = $this->domain;

        $curl = curl_init();
        curl_setopt_array($curl,
            array(
                CURLOPT_URL => $this->api."/files/list",
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST =>"POST",
                CURLOPT_RETURNTRANSFER=>true,
                CURLOPT_POSTFIELDS =>"path=/&domain=".$domain."&type=json",
                CURLOPT_HTTPHEADER => array(
                    "authorization: Bearer ".$token."",
                ),
            ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return false;
        } else {
           $res = json_decode($response, true);
            if ($res['result'] =="success") {
                return true;
            } else {
                return false;
            }
        }
    }


    /**
     * Get List file in folder
     *
     * @since    1.0.0
     */
    public function GetListFile( $path ) {

        $curl = curl_init();
        curl_setopt_array($curl,
            array(
                CURLOPT_URL => $this->api."/files/list",
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST =>"POST",
                CURLOPT_RETURNTRANSFER=>true,
                CURLOPT_POSTFIELDS =>"path=".$path."&domain=".$this->domain."&type=json",
                CURLOPT_HTTPHEADER => array(
                    "authorization: Bearer ".$this->token."",
                ),
            ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return false;
        } else {
            $res = json_decode($response, true);
            if ($res['result'] =="success") {
                return $res['list'];
            } else {
                return false;
            }
        }
    }


}

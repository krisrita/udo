<?php

class AppController extends Base_Contr
{
    public $needLogin = false;

    public function downloadAction()
    {
        $url = array("appstore" => Common_Config::APP_APPSTORE_DOWNLOAD_URL, "apk"=> Common_Config::APP_ANDROID_DOWNLOAD_URL);
        $this->assign("url", $url);
    }

    public function profileAction()
    {
    }

    public function versionAction()
    {
        $appid = (int)$this->get("appid", false);
        $tbl = new DB_Haodu_AppVersion();
        $platform = Yaf_Registry::get("platform");
        $where = $platform == 'android' ? "where android_download_url != ''" : "where iphone_download_url != ''";
        $version = $tbl->scalar("*", $where, "order by id desc");        
        $downloadUrl = $platform == 'android' ? $version['android_download_url'] : $version['iphone_download_url'];

        $data = array(
            'version' => $version['version'], 
            'download_url' => $downloadUrl, 
            'force_update' => (int)$version['force_update'],
            'update_content' => $version['update_content'],
        ); 
        $return = array(
            "errno" => Common_Error::ERROR_SUCCESS, 
            "error" => Common_Error::$errmsg[Common_Error::ERROR_SUCCESS], 
            "data" => $data);
        echo json_encode($return);
        exit;
    }

    /*
     * udo课堂广告页
     *
     */

    public function udoAdAction(){

    }
}
//47,54,66,73
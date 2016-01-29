<?php

class CorpController extends Base_Contr
{
    public $needLogin = false;

    function aboutAction()
    {
        $tbl = new DB_Haodu_AppVersion();
        $platform = Yaf_Registry::get("platform");
        $where = $platform == 'android' ? "where android_download_url != ''" : "where iphone_download_url != ''";
        $version = $tbl->scalar("*", $where, "order by id desc");        
        $downloadUrl = $platform == 'android' ? $version['android_download_url'] : $version['iphone_download_url'];

        $versionios = $this->get('versionios', null);
        $this->assign("version", $version);
        $this->assign("versionios",$versionios);
    }

    function protocolAction()
    {
    }

    function protocol1Action(){

    }

    function copyrightAction()
    {
    }

    function getAdAction()
    {

    }
}
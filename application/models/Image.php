<?php
/**
 * Created by PhpStorm.
 * User: open
 * Date: 2015/5/29
 * Time: 17:08
 */

class ImageModel
{


    public static function getUrl($id)
    {
        $tblImage = new DB_Howdo_Image();
        $image = $tblImage->fetchRow($id);
        if($image) {
            return Common_Config::BASE_URL_IMG . $image['name'];
        }
        return false;
    }

} 
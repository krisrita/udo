<?php

final class Common_Mobile
{
    //判断是否是电脑登录，还是手机登录
    public static function isMobile()
    {
        if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') || strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')){
            return 'ios';
        }else if(strpos($_SERVER['HTTP_USER_AGENT'], 'Android')){
            return 'android';
        }

        return false;
    }

    /**
     * 验证手机号码
     *
     * @param string $mobilePhone
     * @return boolean
     */
    public static function isMobileNo($mobilePhone) 
    {
        if (preg_match("/^13[0-9]{1}[0-9]{8}$|147[0-9]{8}$|15[0-9]{1}[0-9]{8}$|17[0-9]{1}[0-9]{8}$|18[0-9]{1}[0-9]{8}$/", $mobilePhone)) {
            return true;
        } else {
            return false;
        }
    }
}
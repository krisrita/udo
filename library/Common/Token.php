<?php

class Common_Token
{
	const KEY = "howdo";
	const SIGN = "|";

    /**
     * 生成验证码
     * @param int $uid
     * @param string $api
     * @return string
     */
    public static function create($uid, $api) 
    {
        return md5($uid . self::SIGN . $api . self::SIGN . self::KEY);
    }

    /**
     * 验证token
     * @param string $token
     * @param int $uid
     * @param string $api
     * @return boolean
     */
	public static function check($token, $uid, $api)
	{
        $cookie = new User_Cookie(Common_Config::SITE_DOMAIN, Common_Config::SECURE_KEY, Common_Config::SECURE_SIGN);
        $ssotoken = $cookie->gettoken();
        return $token == $ssotoken;
	}
}
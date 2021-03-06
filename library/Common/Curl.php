<?php

/**
 * Class Common_Curl
 */
final class Common_Curl
{
    public static $DEBUG = 0;
    private static $SUCCESS = 1000;

    public static function request($url, $data)
    {
        $ch = curl_init();
        $timeout = 300;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $handles = curl_exec($ch);
        $errno = curl_errno($ch);
        $error = curl_error($ch);        
        curl_close($ch);
        
        self::halt($errno, $error, $url, $data, $handles);

        if(0 == $errno) {
/*            print_r($handles);*/
            $result = json_decode($handles,true);
            /*$result= $handles;*/
            if ($result && $result['code'] == self::$SUCCESS) {
                return $result['data'];
            }

            return $result;
        }

        return false;
    }

    private static function halt($errno, $error, $url, $data, $result)
    {
        if (self::$DEBUG == 1) {
            var_dump(func_get_args());
        }
    }

}

<?php
@session_start();

/**
 * @desc 验证码
 */
class Common_VerifyCode
{

    private static $tag = 'vcode';

    /**
     * @desc 生成验证图片
     */
    public static function verifyImg()
    {
        @header("Expires: -1");
        @header("Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", FALSE);
        @header("Pragma: no-cache");
        @header("Content-type: image/png;charset=utf8");

        //验证码:字符,长,宽,字符集
        $font = "SIMFANG.TTF";
        $authnum = '';
        $img_width = 120;
        $img_height = 50;
        $char_array = array('2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K', 'L', 'M', 'N', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

        //生成码串
        for ($i = 0; $i < 4; $i++) {
            $char = $char_array[rand(0, 31)];
            $str_char[] = $char;
            $authnum .= $char;
        }

        $_SESSION[self::$tag] = $authnum;        //将四位整数验证码绘入图片
        $img = imagecreate($img_width, $img_height);
        imagecolorallocate($img, 255, 255, 255);        //图片底色，imagecolorallocate第1次定义颜色PHP就认为是底色了

        //下面该生成雪花背景了就是生成＊号而已，
        for ($i = 1; $i <= 70; $i++) {
            $t = imagecolorallocate($img, mt_rand(200, 255), mt_rand(200, 255), mt_rand(200, 255));
            imagestring($img, 1, mt_rand(1, $img_width), mt_rand(1, $img_height), "*", $t);
        }

        for ($i = 0; $i < count($str_char); $i++) {
            $textfont = dirname(__FILE__) . "/arial.ttf";
            $t = imagecolorallocate($img, mt_rand(0, 255), mt_rand(0, 150), mt_rand(0, 200));
            imagettftext($img, 20, 0, mt_rand($i * 30, $i * 30 + 10), $img_height / 5 + mt_rand(20, 38), $t, $textfont, $str_char[$i]);
        }
        //加入干扰象素
        for ($i = 0; $i < 300; $i++) {
            $t = imagecolorallocate($img, mt_rand(0, 255), mt_rand(0, 250), mt_rand(0, 200));
            imagesetpixel($img, mt_rand(0, $img_width), mt_rand(0, $img_height), $t);
        }

        imagepng($img);
        imagedestroy($img);
    }

    /**
     * @desc 验证
     * @param string $vcode
     * @return boolean
     */
    public static function verify($vcode)
    {
        $rs = false;
        if (strtolower($_SESSION[self::$tag]) == strtolower($vcode)) {
            $rs = true;
        }
        session_unregister($_SESSION[self::$tag]);
        return $rs;
    }

    /**
     * 指定长度的数字验证码
     * @param int $length
     * @return int
     */
    public static function createDigits($length)
    {
        return rand(pow(10, ($length - 1)), pow(10, $length) - 1);
    }


    public static function createAlnum($length)
    {
        $string = "abcdefghijklmnopqrstuvwxyz0123456789";
        $length = strlen($string);
        $alnum = "";

        for ($i = 0; $i < $length; $i++) {
            $p = rand($length);
            $alnum .= $string[$p];
        }
        return $alnum;
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: Kris
 * Date: 2015/12/17
 * Time: 11:51
 */
class Common_Char
{

    /*
     * 生成任意位数的随机字符串
     */
    public function getRandChar($length){
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol)-1;

        for($i=0;$i<$length;$i++){
            $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }
        return $str;
    }
}
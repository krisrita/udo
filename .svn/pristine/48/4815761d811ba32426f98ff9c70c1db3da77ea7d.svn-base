<?php

class Common_Password
{

    public static function check($password)
    {
        if (preg_match("/^[_\w]{6,20}$/", $password, $match)) {
            return true;
        }  
        
        return false;
    }
}
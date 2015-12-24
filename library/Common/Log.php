<?php

class Common_Log
{

    public static function debug($log)
    {
        $msg = isset($_SERVER["SCRIPT_NAME"]) ? $_SERVER["SCRIPT_NAME"] . "\t" : "";
        $msg .= (isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : "") . "\t" . date("Y-m-d H:i:s") . "\t";
        $msg .= str_replace("\n", " ", $log) . "\n";
        /*error_log($msg, 3, "/data/logs/debug" . date("Ymd") . ".log");*/
    }

    public static function mysql($log)
    {
        $msg = isset($_SERVER["SCRIPT_NAME"]) ? $_SERVER["SCRIPT_NAME"] . "\t" : "";
        $msg .= (isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : "") . "\t" . date("Y-m-d H:i:s") . "\t" . $log . "\n";
        /*error_log($msg, 3, "/data/logs/mysql" . date("Ymd") . ".log");*/
    }
}
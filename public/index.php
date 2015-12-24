<?php

//echo md5('0|paper/list|howdo'); exit;


if (phpversion() >= "5.3") {
	$root = dirname(__DIR__);
} else {
	$root = dirname(dirname(__FILE__));
}


define("APP_PATH",  realpath(dirname(__FILE__) . '/../'));
ini_set('yaf.library', APP_PATH . '/library');
define('SMARTY_SPL_AUTOLOAD', 1);
include( APP_PATH."/library/Smarty/Smarty.class.php" );
include( APP_PATH."/vendor/autoload.php" );


/*$log = $_SERVER["REMOTE_ADDR"] . "\t" . date("Y-m-d H:i:s") . "\t" ;
$log .= $_SERVER["SCRIPT_NAME"] . "\t";
$log .= preg_replace(array("/\n/", "/\s+/"), ' ', print_r($_REQUEST, true)) . "\t";
$log .= preg_replace(array("/\n/", "/\s+/"), ' ', print_r($_COOKIE, true)) . "\n";
error_log($log, 3, "/data/logs/request" . date("Ymd") . ".log");
*/
$loader = Yaf_Loader::getInstance(APP_PATH . '/library/');

$app  = new Yaf_Application(APP_PATH . "/conf/application.ini");
$app -> bootstrap();
$app -> run();

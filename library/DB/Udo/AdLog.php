<?php
/**
 * Created by PhpStorm.
 * User: open
 * Date: 2015/5/29
 * Time: 16:36
 */


class DB_Udo_AdLog extends DB_Udo
{
    protected $tbl_name = "udo_ad_log";
    protected $pri_key = "id";

    public function __construct()
    {
        parent::__construct();
    }
}
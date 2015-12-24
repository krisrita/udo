<?php
/**
 * Created by PhpStorm.
 * User: Kris
 * Date: 2015/11/16
 * Time: 17:33
 */

class DB_Udo_SubLog extends DB_Udo
{
    protected $tbl_name = "udo_sub_log";
    protected $pri_key = "id";

    public function __construct()
    {
        parent::__construct();
    }
}
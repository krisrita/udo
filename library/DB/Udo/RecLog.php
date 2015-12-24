<?php
/**
 * Created by PhpStorm.
 * User: Kris
 * Date: 2015/11/12
 * Time: 17:39
 */
class DB_Udo_RecLog extends DB_Udo
{
    protected $tbl_name = "udo_recommendation_log";
    protected $pri_key = "id";

    public function __construct()
    {
        parent::__construct();
    }
}
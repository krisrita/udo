<?php
/**
 * Created by PhpStorm.
 * User: Kris
 * Date: 2015/11/13
 * Time: 14:55
 */
class DB_Udo_SchoolSubscribe extends DB_Udo
{
    protected $tbl_name = "udo_school_subscribe";
    protected $pri_key = "id";

    public function __construct()
    {
        parent::__construct();
    }
}
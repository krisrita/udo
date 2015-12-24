<?php
/**
 * Created by PhpStorm.
 * User: Kris
 * Date: 2015/11/2
 * Time: 17:17
 */
class DB_Udo_SchoolStatistics extends DB_Udo
{
    protected $tbl_name = "udo_school_statistics";
    protected $pri_key = "id";

    public function __construct()
    {
        parent::__construct();
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Kris
 * Date: 2015/11/12
 * Time: 14:11
 */

class DB_Udo_SchoolRec extends DB_Udo
{
    protected $tbl_name = "udo_school_recommendation";
    protected $pri_key = "id";

    public function __construct()
    {
        parent::__construct();
    }
}
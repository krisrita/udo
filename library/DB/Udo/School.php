<?php
/**
 * Created by PhpStorm.
 * User: Kris
 * Date: 2015/10/16
 * Time: 11:58
 */

class DB_Udo_School extends DB_Udo
{
    protected $tbl_name = "udo_school";
    protected $pri_key = "id";

    public function __construct()
    {
        parent::__construct();
    }
}
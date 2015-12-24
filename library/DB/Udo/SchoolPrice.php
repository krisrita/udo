<?php
/**
 * Created by PhpStorm.
 * User: Kris
 * Date: 2015/12/10
 * Time: 9:24
 */
class DB_Udo_SchoolPrice extends DB_Udo
{
    protected $tbl_name = "udo_school_price";
    protected $pri_key = "id";

    public function __construct()
    {
        parent::__construct();
    }
}
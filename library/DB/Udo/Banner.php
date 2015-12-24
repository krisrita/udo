<?php
/**
 * Created by PhpStorm.
 * User: Kris
 * Date: 2015/11/2
 * Time: 16:08
 */

class DB_Udo_Banner extends DB_Udo
{
    protected $tbl_name = "udo_banner";
    protected $pri_key = "id";

    public function __construct()
    {
        parent::__construct();
    }
}
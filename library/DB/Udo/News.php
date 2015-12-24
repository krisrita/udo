<?php
/**
 * Created by PhpStorm.
 * User: Kris
 * Date: 2015/11/19
 * Time: 16:42
 */
class DB_Udo_News extends DB_Udo
{
    protected $tbl_name = "udo_news";
    protected $pri_key = "id";

    public function __construct()
    {
        parent::__construct();
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Kris
 * Date: 2015/11/23
 * Time: 17:33
 */

class DB_Udo_SearchLog extends DB_Udo
{
    protected $tbl_name = "udo_search_log";
    protected $pri_key = "id";

    public function __construct()
    {
        parent::__construct();
    }
}
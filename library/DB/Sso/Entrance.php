<?php
/**
 * Created by PhpStorm.
 * User: Kris
 * Date: 2015/11/6
 * Time: 20:10
 */
class DB_Sso_Entrance extends DB_Sso
{
    protected $tbl_name = "entrance";
    protected $pri_key = "id";

    public function __construct()
    {
        parent::__construct();
    }
}
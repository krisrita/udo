<?php
/**
 * Created by PhpStorm.
 * User: Kris
 * Date: 2015/11/9
 * Time: 15:42
 */

class DB_Sso_Resource extends DB_Sso
{
    protected $tbl_name = "resource";
    protected $pri_key = "id";

    public function __construct()
    {
        parent::__construct();
    }
}
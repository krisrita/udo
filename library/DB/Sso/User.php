<?php
/**
 * Created by PhpStorm.
 * User: Kris
 * Date: 2015/12/14
 * Time: 16:15
 */
class DB_Sso_User extends DB_Sso
{
    protected $tbl_name = "user";
    protected $pri_key = "id";

    public function __construct()
    {
        parent::__construct();
    }
}
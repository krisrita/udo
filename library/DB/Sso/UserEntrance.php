<?php
/**
 * Created by PhpStorm.
 * User: Kris
 * Date: 2015/11/6
 * Time: 18:53
 */
class DB_Sso_UserEntrance extends DB_Sso
{
    protected $tbl_name = "user_entrance";
    protected $pri_key = "id";

    public function __construct()
    {
        parent::__construct();
    }
}
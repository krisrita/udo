<?php
/**
 * Created by PhpStorm.
 * User: Kris
 * Date: 2015/12/10
 * Time: 9:44
 */
class DB_Udo_UserBought extends DB_Udo
{
    protected $tbl_name = "udo_user_bought";
    protected $pri_key = "id";

    public function __construct()
    {
        parent::__construct();
    }
}
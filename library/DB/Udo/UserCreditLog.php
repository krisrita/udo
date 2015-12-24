<?php
/**
 * Created by PhpStorm.
 * User: Kris
 * Date: 2015/11/6
 * Time: 20:39
 */

class DB_Udo_UserCreditLog extends DB_Udo
{
    protected $tbl_name = "udo_user_credit_log";
    protected $pri_key = "id";

    public function __construct()
    {
        parent::__construct();
    }
}
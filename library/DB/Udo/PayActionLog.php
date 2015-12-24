<?php
/**
 * Created by PhpStorm.
 * User: Kris
 * Date: 2015/12/22
 * Time: 9:17
 */
class DB_Udo_PayActionLog extends DB_Udo
{
    protected $tbl_name = "udo_pay_action_log";
    protected $pri_key = "id";

    public function __construct()
    {
        parent::__construct();
    }
}
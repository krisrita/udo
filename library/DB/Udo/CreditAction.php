<?php
/**
 * Created by PhpStorm.
 * User: Kris
 * Date: 2015/11/6
 * Time: 20:19
 */

class DB_Udo_CreditAction extends DB_Udo
{
    protected $tbl_name = "udo_credit_action";
    protected $pri_key = "id";

    public function __construct()
    {
        parent::__construct();
    }
}
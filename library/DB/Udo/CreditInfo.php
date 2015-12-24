<?php
/**
 * Created by PhpStorm.
 * User: Kris
 * Date: 2015/12/2
 * Time: 16:41
 */
class DB_Udo_CreditInfo extends DB_Udo
{
    protected $tbl_name = "udo_credit_info";
    protected $pri_key = "id";

    public function __construct()
    {
        parent::__construct();
    }
}
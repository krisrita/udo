<?php
/**
 * Created by PhpStorm.
 * User: Kris
 * Date: 2015/12/10
 * Time: 21:47
 */
class DB_Pay_Account extends DB_Pay
{
    protected $tbl_name = "account";
    protected $pri_key = "id";

    public function __construct()
    {
        parent::__construct();
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Kris
 * Date: 2015/12/2
 * Time: 16:44
 */
class DB_Udo_CouponLog extends DB_Udo
{
    protected $tbl_name = "udo_coupon_log";
    protected $pri_key = "id";

    public function __construct()
    {
        parent::__construct();
    }
}
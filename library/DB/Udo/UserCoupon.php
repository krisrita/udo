<?php
/**
 * Created by PhpStorm.
 * User: Kris
 * Date: 2015/12/2
 * Time: 16:43
 */
class DB_Udo_UserCoupon extends DB_Udo
{
    protected $tbl_name = "udo_user_coupon";
    protected $pri_key = "id";

    public function __construct()
    {
        parent::__construct();
    }
}
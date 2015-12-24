<?php
/**
 * Created by PhpStorm.
 * User: Kris
 * Date: 2015/12/2
 * Time: 16:40
 */
class DB_Udo_CoinInfo extends DB_Udo
{
    protected $tbl_name = "udo_coin_info";
    protected $pri_key = "id";

    public function __construct()
    {
        parent::__construct();
    }
}
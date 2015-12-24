<?php
/**
 * Created by PhpStorm.
 * User: Kris
 * Date: 2015/12/2
 * Time: 16:39
 */
class DB_Udo_Order extends DB_Udo
{
    protected $tbl_name = "udo_order";
    protected $pri_key = "id";

    public function __construct()
    {
        parent::__construct();
    }
}
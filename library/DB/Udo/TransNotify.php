<?php
/**
 * Created by PhpStorm.
 * User: Kris
 * Date: 2015/12/14
 * Time: 10:04
 */
class DB_Udo_TransNotify extends DB_Udo
{
    protected $tbl_name = "udo_trans_notify";
    protected $pri_key = "id";

    public function __construct()
    {
        parent::__construct();
    }
}
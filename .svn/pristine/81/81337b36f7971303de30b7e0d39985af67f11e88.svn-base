<?php

class ErrorController extends Base_Contr
{
    
    public $needLogin = false;

    public function indexAction()
    {
        $errno = (int)$this->get("errno", 0);
        $data = array("errno" => $errno, "error" => Common_Error::$errmsg[$errno]);
        $this->assign("data" , $data);
    }
}
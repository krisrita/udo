<?php
class QiniuModel
{
    private $qiniu = null;

    public function __construct($bucket = "image")
    {
        $this->qiniu = new Qiniu_Util();
        $this->qiniu->set_conf(Common_Config::QINIU_ACCESS_KEY, Common_Config::QINIU_SECRET_KEY, $bucket);
    }



    public function uploadImage($fullname, $newName = null )
    {
        if(!$newName) {
            list($dirname, $newName, $ext, $filename) = pathinfo($fullname);
        }
        $rs = $this->qiniu->upload_files($newName , $fullname);
        return $rs;
    }
}
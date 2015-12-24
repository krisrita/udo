<?php
/**
 * 文件上传
 * @author yuanxch
 * @date 2015/6/1
 */
class UploadController extends Base_Contr
{
    /**
     * 图片上传接口
     */
    function imageAction()
    {
        if (!isset($_FILES['file'])) {    
            $this->displayJson(Common_Error::ERROR_UPLOAD_NOT_EXISTS);
        }

        Common_Upload::$sNameing = 'md5';        
        $up = new Common_Upload(
            $_FILES['file'], 
            Common_Config::IMAGE_SAVE_PATH, 
            Common_Config::$allowImageExt, 
            Common_Config::ALLOW_IMAGE_SIZE
         );

        $succ = $up->upload();
        if( $succ > 0 ) {
            $infos = $up->getSaveInfo();           
            $qn = new QiniuModel();
            $file = $infos[0];
            $rs = $qn->uploadImage($file['path'], $file['saveas']);
            if ($rs) {
                $this->displayJson(Common_Error::ERROR_SUCCESS, array("image" => Common_Config::BASE_URL_7NIU . $file['saveas']));
            }
        }

        $this->displayJson(Common_Error::ERROR_UPLOAD);
    }
}
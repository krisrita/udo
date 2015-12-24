<?php

require_once 'qiniu/io.php';
require_once 'qiniu/rs.php';
require_once 'qiniu/conf.php';
require_once 'qiniu/fop.php';
require_once 'qiniu/utils.php';


class Qiniu_Util
{
	private $bucket_name = NULL;
	private $accessKey = NULL;
	private $secretKey = NULL;
	
	 public function upload_files($name,$file){
		Qiniu_SetKeys($this->accessKey, $this->secretKey);
		$putPolicy = new Qiniu_RS_PutPolicy($this->bucket_name.":".$name);
		$upToken = $putPolicy->Token(null);
		$putExtra = new Qiniu_PutExtra();
		list($ret, $err) = Qiniu_PutFile($upToken, $name, $file, $putExtra);
		if ($err !== null) {
			return false;
		} else {
			return true;
		} 
	}

	public function get_token(){
		Qiniu_SetKeys($this->accessKey, $this->secretKey);
		$putPolicy = new Qiniu_RS_PutPolicy($this->bucket_name);
		return $putPolicy->Token(null);
	}
	
	public function set_conf($access,$secret,$name){
		$this->bucket_name = $name;
		$this->accessKey = $access;
		$this->secretKey = $secret;
		return $this;
	}
	
}
?>

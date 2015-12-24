<?php
/**
*  Create On 2014-11-26
*  Author yiwei
*  QQ:1006629314
**/

include_once("CCPRestSDK.php");

class SMS_Util{
	protected $accountSid;
	protected $accountToken;
	protected $appId;
	protected $serverIP;
	protected $serverPort;
	protected $softVersion;
	
	protected $rest;
	
	public function __construct($conf){
		$this->accountSid = $conf['accountSid'];
		$this->accountToken = $conf['accountToken'];
		$this->appId = $conf['appId'];
		$this->serverIP = $conf['serverIP'];
		$this->serverPort = $conf['serverPort'];
		$this->softVersion = $conf['softVersion'];
		
		$this->rest = new CCPRestSDK($this->serverIP,$this->serverPort,$this->softVersion);
		$this->rest->setAccount($this->accountSid,$this->accountToken);
		$this->rest->setAppId($this->appId); 
	}
	
	/**
	 * 发送模板短信
	 * sendTemplateSMS("手机号码","内容数据","模板Id");
	 * @param to 手机号码集合,用英文逗号分开
	 * @param datas 内容数据 格式为数组 例如：array('Marry','Alon')，如不需替换请填 null
	 * @param $tempId 模板Id
	 */
	function sendTemplateSMS($to,$datas,$tempId)
	{
		// 发送模板短信
		//echo "Sending TemplateSMS to $to <br/>";
		$result = $this->rest->sendTemplateSMS($to,$datas,$tempId);
        Common_Log::debug(json_encode($result));
        Common_Log::debug(json_encode($to));
        Common_Log::debug(json_encode($datas));
        Common_Log::debug(json_encode($tempId));
		if($result == NULL ) {
			$info['state'] = '';
	     	$info['msg'] = "发送失败";
	     	return $info;
     	}
	     if($result->statusCode!=0) {
// 		     echo "error code :" . $result->statusCode . "<br>";
// 		     echo "error msg :" . $result->statusMsg . "<br>";
     		//TODO 添加错误处理逻辑
     		$info['state'] = $result->statusCode;
     		$info['msg'] = $result->statusMsg;
     		return $info;
	     		
	     }else{
         //TODO 添加成功处理逻辑
		    // echo "Sendind TemplateSMS success!<br/>";
		     	// 获取返回信息
	         $smsmessage = $result->TemplateSMS;
// 	       	 echo "dateCreated:".$smsmessage->dateCreated."<br/>";
// 	         echo "smsMessageSid:".$smsmessage->smsMessageSid."<br/>";
	         //TODO 添加成功处理逻辑
	         $info['state'] = 0;
	         $info['msg'] = $smsmessage->smsMessageSid;
	         $info['dateCreated'] = $smsmessage->dateCreated;
	         return $info;
	         
	     }
	}
	

	/**
	 * 语音验证码
	 * voiceVerify("验证码内容","循环播放次数","接收号码","显示的主叫号码","营销外呼状态通知回调地址");
	 * @param verifyCode 验证码内容，为数字和英文字母，不区分大小写，长度4-8位
	 * @param playTimes 播放次数，1－3次
	 * @param to 接收号码
	 * @param displayNum 显示的主叫号码
	 * @param respUrl 语音验证码状态通知回调地址，云通讯平台将向该Url地址发送呼叫结果通知
	 */
	function voiceVerify($verifyCode,$playTimes,$to,$displayNum,$respUrl)
	{
		//调用语音验证码接口
		//echo "Try to make a voiceverify,called is $to <br/>";
		$result = $this->rest->voiceVerify($verifyCode,$playTimes,$to,$displayNum,$respUrl);
		if($result == NULL ) {
// 		echo "result error!";
// 			break;
			$info['state'] = '';
			$info['msg'] = "发送失败";
			return $info;
        }
	
			if($result->statusCode!=0) {
// 			echo "error code :" . $result->statusCode . "<br>";
// 				echo "error msg :" . $result->statusMsg . "<br>";
				//TODO 添加错误处理逻辑
				$info['state'] = $result->statusCode;
				$info['msg'] = $result->statusMsg;
				return $info;
        } else{
	      // echo "voiceverify success!<br>";
				// 获取返回信息
				$voiceVerify = $result->VoiceVerify;
//             echo "callSid:".$voiceVerify->callSid."<br/>";
// 				echo "dateCreated:".$voiceVerify->dateCreated."<br/>";
						//TODO 添加成功处理逻辑
				$info['state'] = 0;
				$info['msg'] = $voiceVerify->callSid;
				$info['dateCreated'] = $voiceVerify->dateCreated;
				return $info;
						
		}
	}
	
	     	
	
}

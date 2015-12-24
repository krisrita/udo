 <?php
 @session_start();
 /**
 * 用户相关接口
 */
class UserController extends Base_Contr
{
    public $needLogin = true;

    /**
     * 用户是否存在
     */
    public function isUserExistsAction()
    {
        $mobile = (int)$this->get("mobile", 0);
        $userModel = new UserModel();
        $id = $userModel -> isUserExists($mobile);
        $this->displayJson(Common_Error::ERROR_SUCCESS, array("uid" => (int)$id));
    }

    /**
     * 用户注册 curl -d "mobile=13055290177&password=123456&vcode=123456" http://182.92.110.119/user/register
     */
	public function registerAction()
	{
        $userModel = new UserModel();
        if ($this->uid) {
            $userModel->logout();
            $this->redirect("/user/register");
        }

		if('POST' == $this->getRequest()->getMethod()) {
            $mobile = $this->post()->getAlnum("mobile", false);
			$password = $this->post()->get("password", false);
			$code = $this->post()->getAlnum("vcode", false);
            $name = trim($this->post()->get('name', ''));
            $gender = $this->post()->getAlnum('gender', 0);
            $provId = $this->post()->getAlnum('prov_id', 0);
            $cityId = $this->post()->getAlnum('city_id', 0);
            $areaId = $this->post()->getAlnum('area_id', 0);
            $grade = $this->post()->getAlnum('grade', 0);
            $school = trim($this->post()->get('school', ''));

            if (!$mobile || !$password || !$code) {
                $this->displayJson(Common_Error::ERROR_PARAM);
            }

            if(strlen($password) > 20 || strlen($password) < 6 || !Common_Password::check($password)) {
                $this->displayJson(Common_Error::ERROR_USER_PASSWORD_FORMAT);
            }

            if (!Common_Mobile::isMobileNo($mobile)) {
                $this->displayJson(Common_Error::ERROR_USER_REG_MOBILE);
            }

            if(!$name || is_numeric($name)) {
                $name = 'U' . substr($mobile, -5);
            }

            //如果是手机端需要验证vcode

            $uid = $userModel->register($mobile, $password, $code, $name, $gender, $provId, $cityId, $areaId, $grade, $school);
            if ($uid > 0) {
                $this->displayJson(Common_Error::ERROR_SUCCESS, $userModel->getUser($uid));
            }

            $this->displayJson($uid);
		}
	}

    /**
     * curl -d "mobile=13718188699&password=111111" http://182.92.110.119/user/login
     */
	public function loginAction()
	{
		$request = $this->getRequest();
        $userModel = new UserModel();
        $userModel->logout();

		if('POST' == $request->getMethod()) {
			$mobile = trim($this->post()->get("mobile"));
			$password = trim($this->post()->get("password"));
			//$vcode = trim($this->post()->get("vcode"));
            
            if(!$mobile || !$password) {
                $this->displayJson(Common_Error::ERROR_PARAM);
            }

            if(strlen($password) > 20 || !Common_Password::check($password)) {
                $this->displayJson(Common_Error::ERROR_USER_PASSWORD_FORMAT);
            }

            $userModel = new UserModel();
            $uid = $userModel->login($mobile, $password);

            if($uid == -1) {
                $this->displayJson(Common_Error::ERROR_USER_NOT_EXISTS);
            }

            if($uid == -2) {
                $this->displayJson(Common_Error::ERROR_USER_PASSWORD);
            }

            $this->displayJson(Common_Error::ERROR_SUCCESS, $userModel->getUser($uid));
		}
	}


    /**
     * SSO验证
     */
    function verifyAction()
    {
        $request = $this->getRequest();
        $userModel = new UserModel();
        $userModel->logout();

/*        if('POST' == $request->getMethod())
        {*/
            $token1 = trim($this->post()->get("ssotoken"));
            $token = "token50de97a2-0bd2-4729-86aa-fe991f5ec1306Hf0wjrb";
            if(!$token)
                $this->displayJsonUdo(Common_Error::ERROR_PARAM);

    /*        $user = new UserModel();
            $result = $user ->verify($token);*/


        $url = Common_Config::SSO_VERIFY_URL;
        $domainId = Common_Config::UDO_OP_DOMAINID;

        $secret = Common_Config::UDO_OP_SECRET_SERVER;
        $sign_raw = $domainId.$token.$secret;
        $sign = md5($sign_raw);

        $post_data = array ("domainId" => $domainId,"token" => $token ,"sign"=>$sign);

        //print_r($post_data);
        $cl= new Common_Curl();
        $array = $cl->request($url,$post_data);

        //print_r($array['code']);
        if($array['code']!=null && $array['code']==0)
            $this->displayJsonUdo(Common_Error::ERROR_FAIL,null,$array['msg']."error token:".$token);
        //print_r($array);
        $uid = $array['id'];
       // print_r($uid?$uid:"null");
        //print_r($this->uid);

        $cookie = new User_Cookie(Common_Config::SITE_DOMAIN, Common_Config::SECURE_KEY, Common_Config::SECURE_SIGN);
        $cookie->login($uid);
            $entrances = $array['entrances'];
            //print_r($entrances);
        $this->displayJsonUdo(Common_Error::ERROR_SUCCESS , $entrances);
       // }


    }

    /**
     * SSO验证 重写方法1
     */
    function verify1Action()
    {
        $request = $this->getRequest();
        $userModel = new UserModel();
        $userModel->logout();

        /*        if('POST' == $request->getMethod())
                {*/
        $token1 = trim($this->post()->get("ssotoken"));
        $token = "token332192fc-24ca-4de3-9a6d-9bf60a1fad3bXxmVNNZZ";
        if(!$token)
            $this->displayJsonUdo(Common_Error::ERROR_PARAM);

        $result = $userModel ->verify($token);

        if($result == -1)
            $this->displayJsonUdo(Common_Error::ERROR_FAIL,null,$result['msg']."error token:".$token);

        $entrances = $result['entrances'];
        //print_r($result);
        $this->displayJsonUdo(Common_Error::ERROR_SUCCESS , $entrances);
        // }

    }

    /**
     * 测试方法：SSO验证 测试模拟发送post请求
     */
    public function testverifyAction(){
        $url = "http://182.92.118.115:8080/user/verify";
        $post_data = array ("ssotoken" => "token9ac1734e-cd08-45b3-aade-b6439ad2f960kHdezGH4");
        $cl= new Common_Curl();
        $array = $cl->request($url,$post_data);
        print_r($array);
        $this->displayJsonUdo(Common_Error::ERROR_SUCCESS , $array);
    }

    /**
     * 退出登录
     */
    public function logoutAction()
    {
        Yaf_Dispatcher::getInstance()->disableView();
        $userModel = new UserModel();
        $userModel->logout();
        if ($this->platform != 'web') {
            $this->displayJson(Common_Error::ERROR_SUCCESS);
        }
        header("Location:/user/login?from=logout");
        exit;
    }

	/**
	 * curl -d "mobile=13718188699" http://182.92.110.119/user/sendcode
	 */
	public function sendCodeAction()
	{           
        $mobile = (int)$this->get("mobile", 0);

        if (!Common_Mobile::isMobileNo($mobile)) {
            $this->displayJson(Common_Error::ERROR_USER_REG_MOBILE);
        }


        $userModel = new UserModel();
        $id = $userModel -> isUserExists($mobile);
        if ($id) {
            $this->displayJson(Common_Error::ERROR_USER_EXISTS);
        }

        $model = new UserModel();
        $rs = $model -> sendRegCode($mobile);

        if($rs) {
            $this->displayJson(Common_Error::ERROR_SUCCESS, NULL);
        }
            
        $this->displayJson(Common_Error::ERROR_USER_SEND_CODE, NULL);
	}

    


    /**
     * 用户信息
     */
    public function profileAction()
    {
        if (!$this->uid) {            
        }

        $userModel = new UserModel();
        $user = $userModel->getUser($this->uid);
        
        $this->assign('user', $user);
    }

    /**
     * 忘记密码发送验证码step1
     * curl http://182.92.110.119/user/forgetPasswordSendCode?mobile=13718188699
     */
    public function forgetPasswordSendCodeAction()
    {
        $mobile = (int)$this->get("mobile", 0);
        $userModel = new UserModel();
        $id = $userModel -> isUserExists($mobile);
        if (!$id) {
            $this->displayJson(Common_Error::ERROR_USER_NOT_EXISTS);
        }

        $model = new UserModel();
        $rs = $model -> sendRegCode($mobile);

        if($rs) {
            $this->displayJson(Common_Error::ERROR_SUCCESS, NULL);
        }
            
        $this->displayJson(Common_Error::ERROR_USER_SEND_CODE, NULL);
    }


    /**
     * 忘记密码step2
     * curl -d "mobile=1371818699&code=111111" http://182.92.110.119/user/checkcode
     */
    public function checkcodeAction()
    {
        $mobile = (int)$this->post("mobile", false);
        $code = (int)$this->post("code", false);
        $userModel = new UserModel();
        $errno = $userModel -> checkCode($mobile, $code);
        if ($errno !== true) {
            $this->displayJson($errno);
        }

        $this->displayJson(Common_Error::ERROR_SUCCESS);
     }

    /**
     * 忘记密码step3
     * curl -d "mobile=13055290177&code=798021&password=111111" http://182.92.110.119/forgetpassword
     */
	public function forgetPasswordAction()
	{   
        $userModel = new UserModel();
        if ($this->uid) {
            $userModel->logout();
            $this->redirect("/user/forgetpassword");
        }

		$request = $this->getRequest();
        if('POST' == $request->getMethod()) {
            $mobile = (int)$this->post("mobile", false);
            $code = (int)$this->post("code", false);
            $password = $this->post("password", false);

            if (!$mobile || !$password || !$code) {
                $this->displayJson(Common_Error::ERROR_PARAM);
            }

            if(strlen($password) > 20 || strlen($password) < 6 || !Common_Password::check($password)) {
                $this->displayJson(Common_Error::ERROR_USER_PASSWORD_FORMAT);
            }
            
            $rs = $userModel->forgetPassword($mobile, $password, $code);
            
            if(true === $rs) {
                $this->displayJson(Common_Error::ERROR_SUCCESS, NULL);
            }
                
            $this->displayJson(Common_Error::ERROR_USER_REG_CODE, NULL);            
        }
	}

    /**
     * 改密码
     */
    public function resetPasswordAction()
    {
		$request = $this->getRequest();
        if('POST' == $request->getMethod()) {
            $passwordOld = $this->post("password_old", '');
            $passwordNew = $this->post("password_new", '');
            
            if(strlen($passwordNew) > 20 || strlen($passwordNew) < 6 || !Common_Password::check($passwordNew)) {
                $this->displayJson(Common_Error::ERROR_USER_PASSWORD_FORMAT);
            }

            $userModel = new UserModel();
            $errno = $userModel->resetPassword($this->uid, $passwordOld, $passwordNew);
            $this->displayJson($errno);        
        }
    }


    /**
     * web变更手机号第一步:验证密码
     * curl -d "password=111111" http://182.92.110.119/user/checkpassword?uid=312
     */
    public function checkPasswordAction()
    {
        $password = $this->post("password", false);

        if(strlen($password) > 20 || !Common_Password::check($password)) {
            $this->displayJson(Common_Error::ERROR_USER_PASSWORD_FORMAT);
        }

        $userModel = new UserModel();
        $errno = $userModel -> checkPassword($this->uid, $password);
        if ($errno !== true) {
            $this->displayJson($errno);       
        }

        $this->displayJson(Common_Error::ERROR_SUCCESS);
    }

    /**
     * 变更手机号第一步:验证密码与手机号
     * curl -d "mobile_new=1371818699&password=111111" http://182.92.110.119/user/resetmobilecheck?uid=312
     */
    public function resetMobileCheckAction()
    {
        $password = $this->post("password", false);
        $mobileNew = (int)$this->post("mobile_new", 0);

        $userModel = new UserModel();
        //验证密码
        $errno = $userModel -> checkPassword($this->uid, $password);
        if ($errno !== true) {
            $this->displayJson($errno);       
        }

        //验证手机号是否被注册过
        $id = $userModel -> isUserExists($mobileNew);
        if ($id) {
            $this->displayJson(Common_Error::ERROR_USER_EXISTS);
        }

        $this->displayJson(Common_Error::ERROR_SUCCESS);
    }


    /**
     * 变更手机号第二步
     */
	public function resetMobileAction()
	{
        $request = $this->getRequest();
        if('POST' == $request->getMethod()) {
            $password = $this->post("password", false);
            $code = (int)$this->post("code", false);
            $mobileNew = (int)$this->post("new_mobile", 0);

            $userModel = new UserModel();
            $errno = $userModel -> resetMobile($this->uid, $password, $mobileNew, $code);
            if($errno === true) {
                $this->displayJson(Common_Error::ERROR_SUCCESS);
            }

            $this->displayJson($errno);
        }
	}

    /**
     * 设置头像
     */
    public function setHeadAction()
    {
		$request = $this->getRequest();

        if('POST' == $request->getMethod()) {
 
            if (!isset($_FILES['file']) && !isset($_POST['avator'])) {   
                $this->displayJson(Common_Error::ERROR_UPLOAD_NOT_EXISTS);
            }

            if(isset($_FILES['file'])) {
                Common_Upload::$sNameing = 'md5';        
                $up = new Common_Upload($_FILES['file'], Common_Config::IMAGE_SAVE_PATH, Common_Config::$allowImageExt, Common_Config::ALLOW_IMAGE_SIZE);
                $succ = $up->upload();
                $infos = $up->getSaveInfo(); 
            } else {
                $saveas = md5($_POST['avator']) . ".png";
                $file = array('path' => "/tmp/" . $saveas, "saveas" => $saveas );
                $avator = str_replace("data:image/png;base64,", "", $_POST['avator']);
                $avator = base64_decode($avator);
                $succ = file_put_contents($file['path'], $avator); 
                $infos[] = $file;
            }

            if( $succ > 0 ) {          
                $qn = new QiniuModel(Common_Config::QINIU_BUCKET_AVATOR);
                $file = $infos[0];
                $rs = $qn->uploadImage($file['path'], "avatar/" . $file['saveas']);
                if ($rs) {                
                    $userModel = new UserModel();
                    $rs = $userModel -> setProfile($this->uid, array('avator' => $file['saveas']));
                    if ($rs) {
                        $this->displayJson(Common_Error::ERROR_SUCCESS, array("image" => Common_Config::AVATOR_BASE_URL . $file['saveas']), '上传头像成功');
                    }
                }
            }

            $this->displayJson(Common_Error::ERROR_UPLOAD);
        }
    }

    
    /**
     * 更新用户资料接口
     */
    public function setProfileAction()
	{  
        if('POST' == $this->getRequest()->getMethod()) {
            $data = array();
            $name = $this->post('name', false);
            if($name) $data['stuff_name'] = $name;

            $gender = (int)$this->post('gender', false);
            if($gender) $data['gender'] = $gender;

            $provId = $this->post('prov_id', false);
            if(false !== $provId) $data['prov_id'] = $provId;

            $cityId = $this->post('city_id', false);
            if(false !== $cityId) $data['city_id'] = $cityId;

            $areaId = $this->post('area_id', false);
            if(false !== $areaId) $data['area_id'] = $areaId;

            $grade = $this->post('grade', false);
            if($grade) $data['grade'] = $grade;

            $school = $this->post('school', '');        
            if($school) $data['school'] = $school;
            
            $sign = $this->post('sign', '');        
            if($sign) $data['sentence'] = $sign;
            
            $avator = $this->post('avator', '');        
            if($avator) {
                $pathinfo = pathinfo($avator);
                $data['avator'] = $pathinfo['basename'];
            }
            
            $userModel = new UserModel();
            if(empty($data) || $userModel->setProfile($this->uid, $data)) {
                $this->displayJson(Common_Error::ERROR_SUCCESS);
            }

            $this->displayJson(Common_Error::ERROR_MYSQL_EXECUTE);
        }
	}



}
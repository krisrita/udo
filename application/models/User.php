<?php
/**
 * 用户业务模型
 * @author 449127727@qq.com
 */
class UserModel
{

    /**
     * 当前登录用户的uid
     * @return 用户id
     */
    public function getUid()
    {
        $cookie = new User_Cookie(Common_Config::SITE_DOMAIN, Common_Config::SECURE_KEY, Common_Config::SECURE_SIGN);
        return $cookie->getUid();
    }

    public function getSessionUid()
    {
        return $_SESSION['uid'] ;
    }


    /**
     * 发送验证码
     * @param $mobile
     * @return bool|int
     */
	public function sendRegCode($mobile)
	{
        $code = rand ( 100000, 999999 );     
        $sms = new SMS_Util(Common_Config::$sms);
        $data = array($code, "5");
        $result = $sms->sendTemplateSMS($mobile, $data, 9188);

        if($result && $result['state'] == 0) {
            $time = time();
            $session = array("mobile" => $mobile, "code" => $code, "create_time" => $time);
            //$_SESSION['mc'] = implode("|", $session);
            $tbl = new DB_Haodu_MobileCode();
            $tbl -> insert($session);
            return true;
        }
        return false;
	}

    /**
     * 验证短信码
     */
    public function checkCode($mobile, $code)
    {
        $tbl = new DB_Haodu_MobileCode();
        $session = $tbl -> scalar("mobile,code,create_time", "where mobile={$mobile}", "order by id desc");
        
        if (!$session) {
            return Common_Error::ERROR_USER_REG_CODE_NOT_EXISTS;
        }
        
        $m = $session['mobile'];
        $c = $session['code'];
        $t = $session['create_time'];

        if ($m != $mobile || $c != $code || time() > $t+300) {
            return Common_Error::ERROR_USER_REG_CODE;
        }
        return true;
    }


    /**
     * 用户注册
     * @param $mobile
     * @param $password
     * @return bool|int -1:帐号已存在
     */
	public function register($mobile, $password, $code, $name, $gender, $provId, $cityId, $areaId, $grade, $school)
	{
        if ($this->isUserExists($mobile)) {
            return Common_Error::ERROR_USER_EXISTS;
        }

        $errno = $this->checkCode($mobile, $code);
        if(true !== $errno) {
            return $errno;
        }

        $time = time();
        $salt = rand(10000, 999999);
        $password = md5(md5($password.$salt));
        $data = array(
            'stuff_name' => $name,
            'gender' => $gender,
            'mobile' => $mobile, 
            'salt' => $salt, 
            'password' => $password,
            'prov_id' => $provId,
            'city_id' => $cityId,
            'area_id' => $areaId,
            'grade' => $grade,
            'school' => $school,
            //'create_time' => $time
        );
        
        $tblUser = new DB_Howdo_User();
        $uid = $tblUser->insert($data);

        if ($uid) {
            return $uid;
        }

        return Common_Error::ERROR_USER_REG;
	}

    /**
     * 验证帐号是否存在
     * @param $mobile
     * @return int|false
     */
    public function isUserExists($mobile)
    {
        $tbl = new DB_Howdo_User();
        $user = $tbl->scalar("stuff_id", "where mobile='{$mobile}'");

        return $user ? $user['stuff_id'] : false;
    }


    /**
     * 用户登录
     * @param string $mobile
     * @param string $password
     * @return int -1用户不存在;-2密码错误;用户id
     */
    public function login($mobile, $password)
    {
        $dao = new DB_Howdo_User();
        $user = $dao -> scalar("stuff_id, salt, password", "where mobile='{$mobile}'");
        if(!$user) {
            return -1;
        }

        if($user['password'] != md5(md5($password.$user['salt']))) {
            return -2;
        }

        //写入cookie
        $cookie = new User_Cookie(Common_Config::SITE_DOMAIN, Common_Config::SECURE_KEY, Common_Config::SECURE_SIGN);
        $cookie->login($user['stuff_id']);

/*        $uid = $user['stuff_id'];
        $uname = $user['mobile'];*/

        //print_r($uid);
        /*print_r($this->uid);*/
/*        $session = new User_Session();
        $session->login(array($uid,$uname));*/

        return $user['stuff_id'];
    }

    public function verify($token)
    {
        $url = Common_Config::SSO_SCHOOL_URL;
        $domainId = Common_Config::UDO_OP_DOMAINID;

        $secret = Common_Config::UDO_OP_SECRET_SERVER;
        $sign_raw = $domainId.$token.$secret;
        $sign = md5($sign_raw);
        $post_data = array ("domainId" => $domainId,"ssotoken" => $token ,"sign"=>$sign);

        $cl= new Common_Curl();
        $array = $cl->request($url,$post_data);

        print_r($array);
        //判断是否返回失败信息
        if($array['code'] != null && $array['code'] == 0)
            return -1 ;

        $uid = $array['id'];
        $cookie = new User_Cookie(Common_Config::SITE_DOMAIN, Common_Config::SECURE_KEY, Common_Config::SECURE_SIGN);
        $cookie -> settoken($token);
        $cookie -> login($uid);

        return $array;
    }


    /**
     * 退出登录
     */
    public function logout()
    {
        $cookie = new User_Cookie(Common_Config::SITE_DOMAIN, Common_Config::SECURE_KEY, Common_Config::SECURE_SIGN);
        return $cookie->logout();
    }

    /**
     * 设置个人资料
     */
    public function setProfile($uid, $data)
    {
        $tbl = new DB_Howdo_User();
        return $tbl->update($uid, $data); 
    }


    /**
     * 通过uid获得用户信息
     * @param $id
     * @return array
     */
	public function getUser($uid, $fields = 'stuff_id, stuff_name, mobile, sentence as sign, gender, avator, prov_id, city_id, area_id, grade, school')
	{
		$user = new DB_Howdo_User();
		$user = $user->fetchRow($uid, $fields);        
        
        if($user && $user['prov_id']) {
            $tblRegion = new DB_Howdo_Region();
            $regions = $tblRegion -> fetchAll("region_id,region_name", "where region_id in ({$user['prov_id']},{$user['city_id']},{$user['area_id']})", "order by region_id asc");
            $user['prov'] = @$regions[0];
            $user['city'] = @$regions[1];
            $user['area'] = @$regions[2]; 
        }
        if ($user) {
            $user['avator'] = empty($user['avator']) ? Common_Config::DEFAULT_AVATOR : $user['avator'];
            $user['avator'] = Common_Config::AVATOR_BASE_URL . $user['avator'];
        }

        return $user;
	}

    /**
     * 老师信息
     */
    public function getTeacher($uid)
    {
		$user = new DB_Howdo_User();
		$user = $user->fetchRow($uid, "stuff_id as id, avator, stuff_name as name, gender, job_title, info"); 
        if ($user) {
            $user['avator'] = empty($user['avator']) ? Common_Config::DEFAULT_AVATOR : $user['avator'];
            $user['avator'] = Common_Config::AVATOR_BASE_URL . $user['avator'];
        }
        return $user;
     }


    /**
     * 忘计密码(通过手机)
     */
    public function forgetPassword($mobile, $password, $code)
    {
        $uid = $this->isUserExists($mobile);
        if (!$uid) {
            return Common_Error::ERROR_USER_NOT_EXISTS;
        }


        $errno = $this->checkCode($mobile, $code);
        if(true !== $errno) {
            return $errno;
        }

        $time = time();
        $salt = rand(10000, 999999);
        $password = md5(md5($password . $salt));
        $data['salt'] = $salt;
        $data['password'] = $password;

        $tbl = new DB_Howdo_User();
        return $tbl->update($uid, $data); 
    }

    /**
     * 修改密码
     */
    public function resetPassword($uid, $passwordOld, $passwordNew)
    {
        
        $tblUser = new DB_Howdo_User();
        $user = $tblUser -> scalar("stuff_id, salt, password", "where stuff_id={$uid}");
        if(!$user) {
            return Common_Error::ERROR_USER_NOT_EXISTS;
        }

        if($user['password'] != md5(md5($passwordOld.$user['salt']))) {
            return Common_Error::ERROR_USER_PASSWORD_WRONG;
        }

        $data = array("password" => md5(md5($passwordNew.$user['salt'])));
        $rs =  $tblUser -> update($uid, $data);

        if ($rs) {
            return Common_Error::ERROR_SUCCESS;
        }
        
        return Common_Error::ERROR_MYSQL_EXECUTE;

    }


    /**
     * 验证密码
     * @param int $uid
     * @param string $password
     * @return true
     */
    public function checkPassword($uid, $password)
    {
        $tblUser = new DB_Howdo_User();
        $user = $tblUser -> scalar("stuff_id, salt, password", "where stuff_id={$uid}");
        if(!$user) {
            return Common_Error::ERROR_USER_NOT_EXISTS;
        }

        if($user['password'] != md5(md5($password.$user['salt']))) {
            return Common_Error::ERROR_USER_PASSWORD_WRONG;
        }

        return true;
    }

    /**
     * 重置手机号
     */
    public function resetMobile($uid, $password, $mobileNew, $code)
    {   

        if ($this->isUserExists($mobileNew)) {
            return Common_Error::ERROR_USER_EXISTS;
        }

        $errno = $this->checkPassword($uid, $password);
        if (true !== $errno) {
            return $errno;
        }
    
        $errno = $this->checkCode($mobileNew, $code);
        if (true !== $errno) {
            return $errno;
        }
        
        $tblUser = new DB_Howdo_User();
        return $tblUser -> update($uid, array("mobile" => $mobileNew));
    }

    /**
     * 获取全部用户数据
     * author  lijiannan@howdo.cc
     */
    public function getAllUser($domainId){
        $url = Common_Config::SSO_USER_ENTRY_URL;

        $post_data = array("domainId" => $domainId );

        $cl = new Common_Curl();
        $array = $cl->request($url, $post_data);

        //判断是否返回失败信息
        if (empty($array))
            return -1;

        return $array;
    }

    public function getUserInfo($id){
        $url =Common_Config::SSO_USER_INFO;
        $post_data = array("id"=>$id);
        $cl = new Common_Curl();
        $user_info = $cl ->request($url,$post_data);
        return $user_info;
    }

    function getUserId($ssotoken){
        $url = Common_Config::SSO_SCHOOL_URL;
        $post_data = array("ssotoken"=>$ssotoken);
        $cl = new Common_Curl();
        $array = $cl->request($url, $post_data);

        //如果没有传回有效的数据信息，则将报错信息返回
        //$this->displayJsonUdo(Common_Error::INVALID_TOKEN,"",$uid['msg']);
        if(array_key_exists("data",$array) && !$array['data'])
            return $array;
        //否则传回uid
        $uid = $array['id'];
        return $uid;
    }

    function getUserAuth($userId,$schoolId,$ssotoken = ""){
        $url = Common_Config::SSO_SCHOOL_URL;
        $post_data = array("ssotoken"=>$ssotoken);
        $cl = new Common_Curl();
        $array = $cl->request($url, $post_data);
        $isAuthorized = 0;
        foreach($array['entrances'] as $k=>$val){
            if($val['id']==$schoolId){
                //print_r($val['id']);
                $isAuthorized = $val['isAuthorized'];
                break;
            }
        }
        return $isAuthorized;
    }

    /*
     * 根据userId获取用户名
     */
    function getUserName($uid){
        $tbl = new DB_Sso_User();
        $mobile =  $tbl->scalar("name,mobile","where id = {$uid}");
        if(!$mobile)
            return Common_Error::ERROR_FAIL;
        else
            return $mobile;
    }

}

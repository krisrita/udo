<?php

/**
 * 学校
 */
class SchoolController extends Base_Contr
{

    
    function listAction()
    {
        $cityId = (int)$this->get("city_id", 0);

        if (!$cityId)
            $this->displayJson(Common_Error::ERROR_FAIL);
        else
            $this->displayJsonUdo(Common_Error::ERROR_SUCCESS,$cityId);
        $schoolModel = new SchoolModel();
        $list = $schoolModel -> getSchoolList($cityId, $this->uid);

        if($this->isMobile) {
            $this->displayJson(Common_Error::ERROR_SUCCESS, $list);
        }
    }

    /**
     * 学校地区
     */
    function getCitiesAction()
    {
        $school = new SchoolModel();
        $cities = $school -> getCities();
        $this->displayJson(Common_Error::ERROR_SUCCESS, $cities);
    }

    function personalListAction()
    {
        $url = Common_Config::SSO_LOGIN_URL;
        $domainId = Common_Config::UDO_APP_DOMAINID;
        //$mobile = "18202565706";
        $mobile = $this->get("mobile");
        $pwdRaw = $this->get("password");
        //$pwdRaw = "123456";
        $pwdAes = new Common_AES();
        $keyStr = Common_Config::UDO_AES_KEY;
        $pwdAes->set_key($keyStr);
        $pwdAes->require_pkcs5();
        $pwd = $pwdAes->encrypt($pwdRaw);

        $signType = Common_Config::UDO_OP_SIGNTYPE;
        $secret = Common_Config::UDO_OP_SECRET_APP;
        $sign_raw = $domainId.$mobile.$pwd.$signType.$secret;
        $sign = md5($sign_raw);

        $post_data = array ("domainId" => $domainId,"mobile" => $mobile ,"signType"=>$signType,"pwd"=>$pwd,"sign"=>$sign);

        $cl= new Common_Curl();
        $array = $cl->request($url,$post_data);
        print_r($array);
    }

    function personalLiAction()
    {
        $token1 = $this->get("token");

        $request = $this->getRequest();
        $userModel = new UserModel();
        $userModel->logout();

        if('POST' == $request->getMethod())
            $mobile = trim($this->post()->get("mobile"));

        $url = Common_Config::SSO_VERIFY_URL;
        $domainId = Common_Config::UDO_OP_DOMAINID;
        $token = "tokend86ef2e5-e43b-4077-aafd-f2d3290a9889NHL2GezC";
        $secret = Common_Config::UDO_OP_SECRET_SERVER;
        $sign_raw = $domainId.$token.$secret;
        $sign = md5($sign_raw);

        $post_data = array ("domainId" => $domainId,"token" => $token ,"sign"=>$sign);
        //print_r($post_data);

        $cl= new Common_Curl();
        $array = $cl->request($url,$post_data);

        $uid = $array['id'];

        //print_r($uid);
        //print_r($this->uid);

        $cookie = new User_Cookie(Common_Config::SITE_DOMAIN, Common_Config::SECURE_KEY, Common_Config::SECURE_SIGN);
        $cookie->login($uid);

        //print_r($this->uid);
        /*print_r($_SESSION['uid']);*/
        /*print_r($array);*/
       // print_r($array);
        $entrances = $array['entrances'];
            //print_r($entrances);
        $this->displayJsonUdo(Common_Error::ERROR_SUCCESS , $entrances);


    }

    /*
     * 首页获取频道列表 lijiannan@howdo.cc
     */
    function getSchoolAction()
    {
        $request = $this->getRequest();
        if('POST' == $request->getMethod())
        {
            $ssotoken = $this->post()->get("ssotoken");
            $type = $this->post()->get("type");
            $schoolId = $this->post()->get("schoolId");
        }
        else{
            //$ssotoken = "token439f0036-e5c7-4053-a48e-0c23c91ec41epylmt67C";
            $ssotoken = $this->get("ssotoken");
            $type = $this->get("type");
            $schoolId = $this->get("schoolId");
        }
        $type = $type?$type:1;
        if(!$ssotoken || !$type)
            $this->displayJsonUdo(Common_Error::ERROR_PARAM);
        if($type == 4 && !$schoolId)
            $this->displayJsonUdo(Common_Error::ERROR_PARAM,"","缺失参数schoolId");

        $url = Common_Config::SSO_SCHOOL_URL;
        $post_data = array("ssotoken"=>$ssotoken);

        //获取用户id
        $userModel = new UserModel();
        $schoolModel = new SchoolModel();
        $uid = $userModel->getUserId($ssotoken);
        if(is_array($uid))
            $this->displayJsonUdo(Common_Error::INVALID_TOKEN,"",$uid['msg']);

        $cl = new Common_Curl();
        $array = $cl->request($url, $post_data);
        if(array_key_exists('code', $array) && $array['code'] == 0)
            $this->displayJsonUdo(Common_Error::ERROR_FAIL,null,"SSO没有返回可以浏览的频道噢~");

        $result = [];
        $newArray = [];
        //如果用户是请求所有频道
        if($type == 1){
            //获取到频道后，开始进行频道过滤
            //从本版本开始暂不进行频道过滤
            //$result = $schoolModel->schoolFilter($array);
            //过滤后的频道进行排序
            $result = $schoolModel->schoolOrder($array);

            //print_r($result);
            //print_r($result);
            //过滤后的结果去掉BaseUrl
        }
        //如果用户是获取首三个订阅的频道
        //如果用户是获取所有订阅频道
        elseif ($type == 3 || $type ==2 ){
            $result = $schoolModel->getSubscribe($array['entrances'],$uid,$type);
        }
        //如果用户请求的是某一个频道的信息
        elseif ($type == 4){
            $result = $schoolModel->getSingleSchool($uid,$schoolId,$array['entrances']);
            if($result == -1)
                $this->displayJsonUdo(Common_Error::ERROR_FAIL,"","没有返回有效的频道信息噢~");
        }
        //测试用index
        // $i = 0;

        //在返回的频道列表中的每一项上附加上订阅信息
        foreach($result as $k=>$val) {
            //print_r($val);
            if (is_array($val)){
                //测试用数据
                //$val['isAuthorized']= $val['isAuthorized']?0:1;
                if($type == 2){
                    $val = array_merge($val,array("isSubscribed"=>1));
                }
                else
                    $val = array_merge($val,array("isSubscribed"=>($schoolModel->getIfSub($val['id'],$uid))?1:0));

                //附加频道的定价信息
                $val = array_merge($val,$schoolModel->getSchoolPrice($val['id'],$uid));

                //将SSO返回的无用字段过滤掉
                $index = 0;
                while($key = key($val)){
                    if($key == "apiBaseUrl"){
                        array_splice($val,$index,1);
                        break;
                    }
                    $index++;
                    next($val);
                }
            }
            $newArray[$k] = $val;
        }
        if($type == 4)
            $this->displayJsonUdo(Common_Error::ERROR_SUCCESS , $newArray[0]);
        $this->displayJsonUdo(Common_Error::ERROR_SUCCESS , $newArray);

    }

    /*
     * 获取单个频道的属性信息
     */
    public function getSingleSchoolAction(){

    }


    public function testSchoolAction(){
        $url = "http://127.0.0.1:9097/school/getSchool";
        $post_data = array("ssotoken" =>"token88bb31f0-5e54-43b0-af4e-20efa6df23f7LiH0zZaP","type"=>1);
        $cl= new Common_Curl();
        $array = $cl->request($url,$post_data);
/*        print_r($post_data);
        print_r($url);*/

        if(!$array)
            $this->displayJsonUdo(Common_Error::ERROR_FAIL , "fail");
        $this->displayJsonUdo(Common_Error::ERROR_SUCCESS , $array);
    }

    function getBannerAction()
    {
        $request = $this->getRequest();
        if('POST' == $request->getMethod())
        {
            $ssotoken = $this->post()->get("ssotoken");
        }
        else{
            $ssotoken = $this->get("ssotoken");
        }

        $bannerModel = new SchoolModel();
        $banner = $bannerModel->getBanner();


        if(!$banner)
            $this->displayJsonUdo(Common_Error::ERROR_FAIL , "fail");
        $this->displayJsonUdo(Common_Error::ERROR_SUCCESS , $banner);
    }

    function bannerStatisticsAction()
    {
        $request = $this->getRequest();
        if('POST' == $request->getMethod())
        {
            $ssotoken = $this->post()->get("ssotoken");
            $bannerId = $this->post()->get("bannerId");
        }
        else{
            $ssotoken = $this->get("ssotoken");
            $bannerId = $this->get("bannerId");
        }

        if (!$ssotoken || !$bannerId)
            $this->displayJsonUdo(Common_Error::ERROR_PARAM);

        //获取用户id
        $userModel = new UserModel();
        $uid = $userModel->getUserId($ssotoken);
        if(is_array($uid))
            $this->displayJsonUdo(Common_Error::INVALID_TOKEN,"",$uid['msg']);

        $bannerStatistics = new SchoolModel();
        $insertResult = $bannerStatistics->bannerStatistics($uid,$bannerId);

        if(!$insertResult)
            $this->displayJsonUdo(Common_Error::ERROR_FAIL);
        $this->displayJsonUdo(Common_Error::ERROR_SUCCESS);
    }

    function schoolStatisticsAction()
    {
        $request = $this->getRequest();
        if('POST' == $request->getMethod())
        {
            $ssotoken = $this->post()->get("ssotoken");
            $schoolId = $this->post()->get("schoolId");
            $isAuthorized = $this->post()->get("isAuthorized");
        }
        else{
            $ssotoken = $this->get("ssotoken");
            $schoolId = $this->get("schoolId");
            $isAuthorized = $this->get("isAuthorized");
        }
        $type = 1;
        /*print_r($ssotoken);
        print_r($schoolId);
        print_r($isAuthorized);*/

        if (!$ssotoken || !$schoolId)
            $this->displayJsonUdo(Common_Error::ERROR_PARAM);

        //获取用户id
        $userModel = new UserModel();
        $uid = $userModel->getUserId($ssotoken);
        if(is_array($uid))
            $this->displayJsonUdo(Common_Error::INVALID_TOKEN,"",$uid['msg']);

        $schoolStatistics = new SchoolModel();
        $insertResult = $schoolStatistics->schoolStatistics($uid,$schoolId,$isAuthorized,$type);

        if(!$insertResult)
            $this->displayJsonUdo(Common_Error::ERROR_FAIL);
        $this->displayJsonUdo(Common_Error::ERROR_SUCCESS);
    }

    function getResource1Action(){
        $request = $this->getRequest();
        if('POST' == $request->getMethod())
        {
            $userId = $this->post()->get("userId");
            $domainId = $this->post()->get("domainId");
        }
        else{
            $userId = $this->get("userId");
            $domainId = $this->get("domainId");
        }

        if( !$userId || !$domainId)
            $this->displayJsonUdo(Common_Error::ERROR_PARAM);

        $tradeModel = new TradeModel();
        $result = $tradeModel->getPrice1($userId,$domainId);
        //print_r($result);
        $this->displayJsonUdo(Common_Error::ERROR_SUCCESS,$result);
    }


    /*
     * 获取课程及以下各级资源的定价等基本信息
     * 参数：localId父级资源的id;localType：请求资源的本地类型
     */
    function getResourceAction(){
        $request = $this->getRequest();
        if('POST' == $request->getMethod())
        {
            $userId = $this->post()->get("userId");
            $domainId = $this->post()->get("domainId");
            $localId = $this->post()->get("localId");
            $localType = $this->post()->get("localType");
            //$level = $this->post()->get("level");
        }
        else{
            $userId = $this->get("userId");
            $domainId = $this->get("domainId");
            $localId = $this->get("localId");
            $localType = $this->get("localType");
            //$level = $this->get("level");
        }

        if( !$userId || !$domainId )
            $this->displayJsonUdo(Common_Error::ERROR_PARAM);

        $tradeModel = new TradeModel();
        //$parent = $tradeModel->getParentId($localId,$domainId,$localType);

        //$parentId = $parent['parent_id'];

        //print_r(Common_Config::UDO_LOCAL_CHAPTER_TYPE);
        //请求的如果是章资源或者节资源，那么首先需要根据请求的资源本地id获取到ssoid
        if( $localType == Common_Config::UDO_LOCAL_CHAPTER_TYPE || $localType == Common_Config::UDO_LOCAl_SECTION_TYPE){
            $ssoId = $tradeModel->getSsoId($localId,$domainId,$localType);
            if(!$ssoId)
                $this->displayJsonUdo(Common_Error::ERROR_FAIL,"","您请求的资源尚未在云平台上注册哦");
            $parentId = $ssoId['id'];
        }
        //如果请求的是课程资源，由于父级id是0，不需要转化
        else
            $parentId = $localId;
        $result = $tradeModel->getPrice($userId,$domainId,$parentId,$localType);
        //$result = $tradeModel->getPrice($userId,$domainId,$localId,$localType);
        //print_r($result);
        $this->displayJsonUdo(Common_Error::ERROR_SUCCESS,$result);
    }

    /*
     * 给资源定价
     */
    function priceAction(){
        $tradeModel = new TradeModel();
        $tradeModel->price();
    }

    /*
     * 添加用户购买信息
     */
    function boughtAction(){
        $tradeModel = new TradeModel();
        $tradeModel->testBought();
    }

    /*
     *测试数据，添加用户账户信息
     */
    function accountAction(){
        $tradeModel = new TradeModel();
        $tradeModel->testAccount();
    }

    /*
     * 首页获取编辑推荐列表
     */
    function getRecommendationAction(){
        $request = $this->getRequest();
        if('POST' == $request->getMethod())
        {
            $ssotoken = $this->post()->get("ssotoken");
            $actionId = $this->post()->get("actionId");
        }
        else{
            $ssotoken = $this->get("ssotoken");
            $actionId = $this->get("actionId");
        }

        if (!$ssotoken || !$actionId )
            $this->displayJsonUdo(Common_Error::ERROR_PARAM);

        //获取用户id
        $userModel = new UserModel();
        $uid = $userModel->getUserId($ssotoken);
        if(is_array($uid))
            $this->displayJsonUdo(Common_Error::INVALID_TOKEN,"",$uid['msg']);

        $schoolModel = new SchoolModel();
        $rec = $schoolModel->getRec($uid,$actionId);
        //print_r($rec);
        $schoolModel->recLog($uid,$actionId,$rec['rec'],$rec['page']);

        if($rec)
            $this->displayJsonUdo(Common_Error::ERROR_SUCCESS,$rec['rec']);
        else
            $this->displayJsonUdo(Common_Error::ERROR_FAIL);
    }

    /*
     * 点击编辑推荐记录访问日志
     */
    function enterRecommendationAction(){
        $request = $this->getRequest();
        if('POST' == $request->getMethod())
        {
            $ssotoken = $this->post()->get("ssotoken");
            $recId = $this->post()->get("recId");
        }
        else{
            $ssotoken = $this->get("ssotoken");
            $recId = $this->get("recId");
        }

        if (!$ssotoken || !$recId )
            $this->displayJsonUdo(Common_Error::ERROR_PARAM);

        //获取用户id
        $userModel = new UserModel();
        $uid = $userModel->getUserId($ssotoken);
        if(is_array($uid))
            $this->displayJsonUdo(Common_Error::INVALID_TOKEN,"",$uid['msg']);
        $actionId = 3;

        $rec = array(0=>array("recId"=>$recId));
        $schoolModel = new SchoolModel();
        $schoolModel -> recLog($uid,$actionId,$rec,0);

        $this->displayJsonUdo(Common_Error::ERROR_SUCCESS);
    }

    /*
     * 点击进行频道订阅/取消订阅
     */
    function subscribeAction(){
        $request = $this->getRequest();
        if('POST' == $request->getMethod())
        {
            $ssotoken = $this->post()->get("ssotoken");
            $actionId = $this->post()->get("actionId");
            $schoolId = $this->post()->get("schoolId");
        }
        else{
            $ssotoken = $this->get("ssotoken");
            $actionId = $this->get("actionId");
            $schoolId = $this->get("schoolId");
        }

        if (!$ssotoken || !$actionId ||!$schoolId)
            $this->displayJsonUdo(Common_Error::ERROR_PARAM);

        //获取用户id
        $userModel = new UserModel();
        $uid = $userModel->getUserId($ssotoken);
        if(is_array($uid))
            $this->displayJsonUdo(Common_Error::INVALID_TOKEN,"",$uid['msg']);

        //订阅/取消订阅频道
        $schoolModel = new SchoolModel();
        $sub = $schoolModel->subscribe($uid,$actionId,$schoolId);

        switch ($sub){
            case 1:
                $msg = "订阅成功！";
                break;
            case 2:
                $msg = "订阅成功！";
                break;
            case 3:
                $msg = "您已经订阅过该频道啦~~";
                break;
            case 4:
                $msg = "取消成功~~";
                break;
            case 5:
                $msg = "您已经取消过该频道啦~~";
                break;
            case 6:
                $msg = "取消失败";
                break;
            case 7:
                $msg = "订阅失败了~~再试一次吧~~";
                break;
            case 8:
                $msg = "取消失败";
                break;
            default:
                $msg = "失败了~~";
                break;
        }

        $this->displayJsonUdo(Common_Error::ERROR_SUCCESS,"",$msg);

    }

    /*
     * testUpdate
     */
    function testUpdateAction(){
        $upateModel = new SchoolModel();
        $update = $upateModel->testUpdate();
        $this->displayJsonUdo(Common_Error::ERROR_SUCCESS,$update);
    }

    /*
     * 获取资讯:1-首页获取固定数量的资讯;2-列表获取所有
     */
    function getNewsAction(){
        $request = $this->getRequest();
        if('POST' == $request->getMethod())
        {
            $ssotoken = $this->post()->get("ssotoken");
            $actionId = $this->post()->get("actionId");
            $previousId = $this->post()->get("previousId");
            $pageSize = $this->post()->get("pageSize");
        }
        else{
            $ssotoken = $this->get("ssotoken");
            $actionId = $this->get("actionId");
            $previousId = $this->get("previousId");
            $pageSize = $this->get("pageSize");
        }

        if (!$ssotoken || !$actionId)
            $this->displayJsonUdo(Common_Error::ERROR_PARAM);

        //获取用户id
        $userModel = new UserModel();
        $uid = $userModel->getUserId($ssotoken);
        if(is_array($uid))
            $this->displayJsonUdo(Common_Error::INVALID_TOKEN,"",$uid['msg']);
        $schoolModel = new SchoolModel();
        $news = $schoolModel->getNews($actionId,$previousId,$pageSize);

        if($news)
            $msg = "请求成功~";
        else
            $msg = "没有更多了哦~";
        $this->displayJsonUdo(Common_Error::ERROR_SUCCESS,$news,$msg);
    }

    /*
     * 记录用户的热点访问日志
     */

    function enterNewsAction(){
        $request = $this->getRequest();
        if('POST' == $request->getMethod())
        {
            $ssotoken = $this->post()->get("ssotoken");
            $newsId = $this->post()->get("newsId");
        }
        else{
            $ssotoken = $this->get("ssotoken");
            $newsId = $this->get("newsId");
        }

        if (!$ssotoken || !$newsId)
            $this->displayJsonUdo(Common_Error::ERROR_PARAM);

        //获取用户id
        $userModel = new UserModel();
        $uid = $userModel->getUserId($ssotoken);
        if(is_array($uid))
            $this->displayJsonUdo(Common_Error::INVALID_TOKEN,"",$uid['msg']);
        $schoolModel = new SchoolModel();
        $schoolModel -> newsLog($uid,$newsId);

        $this->displayJsonUdo(Common_Error::ERROR_SUCCESS);
    }

    /*
     * 频道搜索
     */
    function searchSchoolAction(){
        $request = $this->getRequest();
        if('POST' == $request->getMethod())
        {
            $ssotoken = $this->post()->get("ssotoken");
            $keyword = $this->post()->get("keyword");
        }
        else{
            $ssotoken = $this->get("ssotoken");
            $keyword = $this->get("keyword");
        }

        if (!$ssotoken || !$keyword)
            $this->displayJsonUdo(Common_Error::ERROR_PARAM);

        //获取用户id
        $userModel = new UserModel();
        $uid = $userModel->getUserId($ssotoken);
        if(is_array($uid))
            $this->displayJsonUdo(Common_Error::INVALID_TOKEN,"",$uid['msg']);
        $schoolModel = new SchoolModel();
        $result = $schoolModel->searchSchool($keyword,$ssotoken);
        if($result == -1)
            $this->displayJsonUdo(Common_Error::ERROR_NO_SEARCH_RESULT,"","搜索结果为空");

        $this->displayJsonUdo(Common_Error::ERROR_SUCCESS,$result);
    }

    /*
     * 频道搜索测试
     * */
    function testSearchAction(){
        $url = "http://182.92.118.115:8080/school/searchSchool/";
        $post_data = array("ssotoken"=>"token88bb31f0-5e54-43b0-af4e-20efa6df23f7LiH0zZaP","keyword"=>"外");

        $cl= new Common_Curl();
        $array = $cl->request($url,$post_data);
        print_r($array);
        $this->displayJsonUdo(Common_Error::ERROR_SUCCESS,$array);
    }

}

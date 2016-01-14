<?php
/**
 * Created by PhpStorm.
 * User: Kris
 * Date: 2015/12/10
 * Time: 21:43
 */
class AccountController extends Base_Contr
{
    public $needLogin = false;

    /*
     * 获取用户的账户余额
     */
    public function getBalanceAction()
    {
        $request = $this->getRequest();
        if('POST' == $request->getMethod())
        {
            $ssotoken = $this->post()->get("ssotoken");
        }
        else{
            $ssotoken = $this->get("ssotoken");
        }

        if (!$ssotoken )
            $this->displayJsonUdo(Common_Error::ERROR_PARAM);

        //获取用户id
        /*$userModel = new UserModel();
        $uid = $userModel->getUserId($ssotoken);
        if(is_array($uid))
            $this->displayJsonUdo(Common_Error::INVALID_TOKEN,"",$uid['msg']);
        //print_r($uid);
        $accountModel = new AccountModel();
        $balance = $accountModel->getBalance($uid)['balance'][0];*/
        $accountModel = new AccountModel();
        $balance = $accountModel->getSsoBalance($ssotoken);

        if($balance == -1)
            $this->displayJsonUdo(Common_Error::ERROR_FAIL,"","账户信息不存在");
        else
            $this->displayJsonUdo(Common_Error::ERROR_SUCCESS,$balance);
    }

    /*
     *获取U币兑换人民币信息
     *
     */
    public function coinInfoAction(){
        $request = $this->getRequest();
        if('POST' == $request->getMethod())
        {
            $ssotoken = $this->post()->get("ssotoken");
        }
        else{
            $ssotoken = $this->get("ssotoken");
        }

        if (!$ssotoken )
            $this->displayJsonUdo(Common_Error::ERROR_PARAM);

        $accountModel = new AccountModel();
        $coinInfo = $accountModel->getCoinInfo();
        $this->displayJsonUdo(Common_Error::ERROR_SUCCESS,$coinInfo);
    }

    /*
     * 获取学分和U币兑换信息
     */
    public function coinCreditInfoAction(){
        $request = $this->getRequest();
        if('POST' == $request->getMethod())
        {
            $ssotoken = $this->post()->get("ssotoken");
        }
        else{
            $ssotoken = $this->get("ssotoken");
        }

        if (!$ssotoken )
            $this->displayJsonUdo(Common_Error::ERROR_PARAM);

        //获取用户id
        $userModel = new UserModel();
        $uid = $userModel->getUserId($ssotoken);
        if(is_array($uid))
            $this->displayJsonUdo(Common_Error::INVALID_TOKEN,"",$uid['msg']);
        $accountModel = new AccountModel();
        $creditInfo = $accountModel->getCreditInfo($uid);
        $this->displayJsonUdo(Common_Error::ERROR_SUCCESS,$creditInfo);
    }

    /*
     * 用户用U币兑换学分
     */
    function coinScoreAction(){
        $request = $this->getRequest();
        if('POST' == $request->getMethod())
        {
            $ssotoken = $this->post()->get("ssotoken");
            $id =  $this->post()->get("id");
        }
        else{
            $ssotoken = $this->get("ssotoken");
            $id =  $this->get("id");
        }

        if (!$ssotoken )
            $this->displayJsonUdo(Common_Error::ERROR_PARAM);

        //获取用户id
        $userModel = new UserModel();
        $uid = $userModel->getUserId($ssotoken);
        if(is_array($uid))
            $this->displayJsonUdo(Common_Error::INVALID_TOKEN,"",$uid['msg']);
        $accountModel = new AccountModel();
        $coinScore = $accountModel->coinScore($id,$uid);
        $this->displayJsonUdo($coinScore);
    }

    /*
     * 获取已购课程信息
     */
    function getBoughtAction(){
        $request = $this->getRequest();
        if('POST' == $request->getMethod())
        {
            $ssotoken = $this->post()->get("ssotoken");
            $previousId = $this->post()->get("previousId");
            $pageSize = $this->post()->get("pageSize");
        }
        else{
            $ssotoken = $this->get("ssotoken");
            $previousId = $this->get("previousId");
            $pageSize = $this->get("pageSize");
        }

        if (!$ssotoken ||!$pageSize && (!$previousId) )
            $this->displayJsonUdo(Common_Error::ERROR_PARAM);

        //获取用户id
        $userModel = new UserModel();
        $uid = $userModel->getUserId($ssotoken);
        if(is_array($uid))
            $this->displayJsonUdo(Common_Error::INVALID_TOKEN,"",$uid['msg']);
        $accountModel = new AccountModel();
        $bought = $accountModel->getBought($uid,$previousId,$pageSize)['bought'];
        if(!$bought && !$previousId)
            $this->displayJsonUdo(Common_Error::ERROR_SUCCESS,$bought,"暂无已购课程哦~~");
        else if(!$bought && $previousId)
            $this->displayJsonUdo(Common_Error::ERROR_SUCCESS,$bought,"没有更多了哦~~");
        else
            $this->displayJsonUdo(Common_Error::ERROR_SUCCESS,$bought);
    }

    /*
     * 已购课程搜索
     */
    function searchBoughtAction(){
        $request = $this->getRequest();
        if('POST' == $request->getMethod())
        {
            $ssotoken = $this->post()->get("ssotoken");
            $key = $this->post()->get("key");
        }
        else{
            $ssotoken = $this->get("ssotoken");
            $key = $this->get("key");
        }

        if (!$ssotoken ||!$key )
            $this->displayJsonUdo(Common_Error::ERROR_PARAM);

        //获取用户id
        $userModel = new UserModel();
        $uid = $userModel->getUserId($ssotoken);
        if(is_array($uid))
            $this->displayJsonUdo(Common_Error::INVALID_TOKEN,"",$uid['msg']);
        $accountModel = new AccountModel();
        $bought = $accountModel->searchBought($uid,$key);
        if(!$bought)
            $this->displayJsonUdo(Common_Error::ERROR_SUCCESS,$bought,"该条件下没有搜索结果哦~~");
        else
            $this->displayJsonUdo(Common_Error::ERROR_SUCCESS,$bought);
    }

    /*
     *行为记录-从已购列表进入课程
     */
    function enterCourseAction(){
        $request = $this->getRequest();
        if('POST' == $request->getMethod())
        {
            $ssotoken = $this->post()->get("ssotoken");
            $courseId = $this->post()->get("courseId");
            $schoolId = $this->post()->get("schoolId");
        }
        else{
            $ssotoken = $this->get("ssotoken");
            $courseId = $this->get("courseId");
            $schoolId = $this->get("schoolId");
        }

        if (!$ssotoken ||!$courseId ||!$schoolId )
            $this->displayJsonUdo(Common_Error::ERROR_PARAM);

        //获取用户id
        $userModel = new UserModel();
        $uid = $userModel->getUserId($ssotoken);
        if(is_array($uid))
            $this->displayJsonUdo(Common_Error::INVALID_TOKEN,"",$uid['msg']);

        //记录进入已购课程的日志类型和位置
        $type = Common_Config::LOG_ENTER_BOUGHT;
        $pos = Common_Config::POS_BOUGHT_COURSE;
        $resource = json_encode(array("courseId"=>$courseId,"schoolId"=>$schoolId));
        $action = 0;
        $accountModel = new AccountModel();
        $insert = $accountModel->orderLog($uid,$type,$pos,$resource,$action);
        if(!$insert)
            $this->displayJsonUdo(Common_Error::ERROR_FAIL);
        $this->displayJsonUdo(Common_Error::ERROR_SUCCESS);
    }

    /*
     *分页获取U币交易日志
     */
    function tradeInfoAction(){
        $request = $this->getRequest();
        if('POST' == $request->getMethod())
        {
            $ssotoken = $this->post()->get("ssotoken");
            $previousId = $this->post()->get("previousId");
            $pageSize = $this->post()->get("pageSize");
        }
        else{
            $ssotoken = $this->get("ssotoken");
            $previousId = $this->get("previousId");
            $pageSize = $this->get("pageSize");
        }

        if (!$ssotoken ||!$pageSize && (!$previousId) )
            $this->displayJsonUdo(Common_Error::ERROR_PARAM);

        //获取用户id
        $userModel = new UserModel();
        $uid = $userModel->getUserId($ssotoken);
        if(is_array($uid))
            $this->displayJsonUdo(Common_Error::INVALID_TOKEN,"",$uid['msg']);
        $accountModel = new AccountModel();
        $coinLog = $accountModel->getTradeInfo($uid,$previousId,$pageSize)['coinLog'];
        if(!$coinLog && !$previousId)
            $this->displayJsonUdo(Common_Error::ERROR_SUCCESS,$coinLog,"暂无操作日志哦~~");
        else if(!$coinLog && $previousId)
            $this->displayJsonUdo(Common_Error::ERROR_SUCCESS,$coinLog,"没有更多了哦~~");
        else
            $this->displayJsonUdo(Common_Error::ERROR_SUCCESS,$coinLog);
    }

    /*
     *分页获取学分日志
     */
    function creditLogAction(){
        $request = $this->getRequest();
        if('POST' == $request->getMethod())
        {
            $ssotoken = $this->post()->get("ssotoken");
            $previousId = $this->post()->get("previousId");
            $pageSize = $this->post()->get("pageSize");
        }
        else{
            $ssotoken = $this->get("ssotoken");
            $previousId = $this->get("previousId");
            $pageSize = $this->get("pageSize");
        }

        if (!$ssotoken ||!$pageSize && (!$previousId) )
            $this->displayJsonUdo(Common_Error::ERROR_PARAM);

        //获取用户id
        $userModel = new UserModel();
        $uid = $userModel->getUserId($ssotoken);
        if(is_array($uid))
            $this->displayJsonUdo(Common_Error::INVALID_TOKEN,"",$uid['msg']);
        $accountModel = new AccountModel();
        $creditLog = $accountModel->getCreditLog($uid,$previousId,$pageSize)['creditLog'];
        if(!$creditLog && !$previousId)
            $this->displayJsonUdo(Common_Error::ERROR_SUCCESS,$creditLog,"暂无操作日志哦~~");
        else if(!$creditLog && $previousId)
            $this->displayJsonUdo(Common_Error::ERROR_SUCCESS,$creditLog,"没有更多了哦~~");
        else
            $this->displayJsonUdo(Common_Error::ERROR_SUCCESS,$creditLog);
    }

    /*
     * 行为记录-用户进入订单页面
     */
    function orderInfoAction(){
        $request = $this->getRequest();
        if('POST' == $request->getMethod())
        {
            $ssotoken = $this->post()->get("ssotoken");
            $type = $this->post()->get("type");
            $pos = $this->post()->get("pos");
            $resource = $this->post()->get("resource");
            $action = $this->post()->get("action");
        }
        else{
            $ssotoken = $this->get("ssotoken");
            $type = $this->get("type");
            $pos = $this->get("pos");
            $resource = $this->get("resource");
            $action = $this->get("action");
        }

        //参数完整性校验
        if (!$ssotoken ||!$type ||!$pos)
            $this->displayJsonUdo(Common_Error::ERROR_PARAM);

        if($type == Common_Config::LOG_ENTER_ORDER){
            if(!is_array(json_decode($resource)))
                $this->displayJsonUdo(Common_Error::ERROR_PARAM,"","缺少resource参数信息");
        }
        else if($type==Common_Config::LOG_SELECT_ALL || $type ==Common_Config::LOG_SELECT_PAY){
            if(!$action)
                $this->displayJsonUdo(Common_Error::ERROR_PARAM,"","缺少action参数信息");
        }

        //获取用户id
        $userModel = new UserModel();
        $uid = $userModel->getUserId($ssotoken);
        if(is_array($uid))
            $this->displayJsonUdo(Common_Error::INVALID_TOKEN,"",$uid['msg']);
        $accountModel = new AccountModel();
        $insert = $accountModel->orderLog($uid,$type,$pos,$resource,$action);
        if(!$insert)
            $this->displayJsonUdo(Common_Error::ERROR_FAIL);
        $this->displayJsonUdo(Common_Error::ERROR_SUCCESS);
    }

    /*
 * 测试上述的行为记录函数
 */
    function testOrderLogAction(){
        $url = "http://182.92.118.115:8080/Account/orderInfo/";
        $ssotoken = "token02ac99d6-d140-43bb-949a-b521a861ebd5ZHj3L6SH";
        //$ssotoken = ""
        $resource = [];
        array_push($resource,array("resourceId"=>1));
        $type = 1;
        $pos = 1;
        $json = json_encode($resource);
        print_r($json);

        $post_data = array("ssotoken" =>$ssotoken,"type"=>$type,"pos"=>$pos,"resource"=>$json);
        $cl= new Common_Curl();
        $array = $cl->request($url,$post_data);
        print_r($array);
        $this->displayJsonUdo(Common_Error::ERROR_SUCCESS,$array);
    }

    /*
     * 支付功能
     */
    function payAction(){
        $request = $this->getRequest();
        if('POST' == $request->getMethod())
        {
            $ssotoken = $this->post()->get("ssotoken");
            $payType = $this->post()->get("payType");
            $resource = $this->post()->get("resource");
            $amt = $this->post()->get("amt");
            $coinId = $this->post()->get("coinId");
            $platform = $this->post()->get("channel");
            $schoolId = $this->post()->get("schoolId");
        }
        else{
            $ssotoken = $this->get("ssotoken");
            $payType = $this->get("payType");
            $resource = $this->get("resource");
            $amt = $this->get("amt");
            $coinId = $this->get("coinId");
            $platform = $this->get("channel");
            $schoolId = $this->get("schoolId");
        }

        //此处为测试数据
        $resource = json_decode($resource,true);

        //接收参数判断
        if (!$ssotoken ||!$payType ||!$amt)
            $this->displayJsonUdo(Common_Error::ERROR_PARAM,"","缺少必选参数");

        $courseCount = 0;
        $schoolModel = new SchoolModel();
        //余额支付时，需对resource参数进行判断
        if ($payType == Common_Config::UDO_PAYTYPE_COIN || $payType== Common_Config::UDO_PAYTYPE_CREDIT) {
            if (!is_array($resource) || !$schoolId)
                $this->displayJsonUdo(Common_Error::ERROR_PARAM, "", "缺少resource或schoolId参数");

            //从客户端传过来的resource是所有选中的资源
            //此处根据resource生成交易的信息
            //$resourceType = 0;
            $resourceInfo = "";
            $courseName = $schoolModel->getSingleCourse($resource[0]['resourceId']);

            foreach ($resource as $k => $value) {
                if ($value['resourceType'] == Common_Config::UDO_RESOURCE_COURSE) {
                    $courseCount++;
                    //$resourceType = Common_Config::UDO_RESOURCE_SCHOOL;
                    $resourceInfo = "频道";
                }
            }
            $resourceInfo = $courseName['name']."'等".$courseCount . "个课程";
        }

        //支付平台支付时，需对platform参数进行判断
        elseif($payType == Common_Config::UDO_PAYTYPE_RECHARGE)
            if(!$coinId || !$platform)
                $this->displayJsonUdo(Common_Error::ERROR_PARAM,"","缺少channel或coinId参数");


        $accountModel = new AccountModel();
        $tradeModel = new TradeModel();
        $userModel = new UserModel();

        //在校验过参数完整性后，生成订单
        $uid = $userModel->getUserId($ssotoken);
        if(is_array($uid))
            $this->displayJsonUdo(Common_Error::INVALID_TOKEN,"",$uid['msg']);
        $order = $accountModel->newOrder($uid,$schoolId,$courseCount,$payType,$resource,$coinId,$amt,$platform);

        //对生成订单的结果进行判断
        if($order < 0)
            $this->displayJsonUdo(Common_Error::ERROR_SUCCESS,array("result"=>$order));

        //生成订单后，将支付参数传向公共云
        //根据支付类型不同，在调用公共云的支付服务时，传相应的值处理
        /*
         * @param payType
         * 1:U币支付
         * 2:U币充值
         * 3.学分支付
         */
        switch($payType){
            case Common_Config::UDO_PAYTYPE_COIN:
                $type = Common_Config::PUBLIC_PAYTYPE_AMOUNT;
                $subject = "U币购买'".$resourceInfo;
                $score = 0;
                $balanceAmt = $amt;
                $channel = 0;
                $chargeAmt = 0;
                break;
            case Common_Config::UDO_PAYTYPE_RECHARGE:
                //客户端提交的amt均为
                $type = $type = Common_Config::PUBLIC_PAYTYPE_CHANNEL;
                $coinMoney = $tradeModel->getCoinMoney($coinId);
                //实际
                $chargeAmt = $amt;
                $amt = $coinMoney['price']/1000;
                $subject = "U币充值";
                $score = 0;
                $balanceAmt = 0;
                $channel = $platform;
                break;
            case Common_Config::UDO_PAYTYPE_CREDIT:
                $type = Common_Config::PUBLIC_PAYTYPE_AMOUNT;
                $subject = "学分购买'".$resourceInfo;
                $score = $amt;
                $amt = 0;
                $balanceAmt = 0;
                $channel = 0;
                $chargeAmt = 0;
                break;
        }

        //notifyUrl:需腰写在配置文件里
        $notifyUrl = Common_Config::PAY_NOTIFY_URL;
        $remark = "支付";

        //公共云传回预下单信息
        $result = $accountModel->pay($ssotoken,$type,$subject,$amt,$chargeAmt,$score,$balanceAmt,$channel,$notifyUrl,$remark);
        switch($payType){
            case Common_Config::UDO_PAYTYPE_COIN:
            case Common_Config::UDO_PAYTYPE_CREDIT:
                if($result){
                    //支付成功，写入购买关系表，U币和学分变动表
                    $insertBought = $accountModel->insertBought($uid,$schoolId,$resource,$order,$result['transNo']);
                    $accountModel->insertTransLog($uid,0-($payType == Common_Config::UDO_PAYTYPE_CREDIT?$score:$amt),$subject,
                        $payType == Common_Config::UDO_PAYTYPE_CREDIT?Common_Config::CREDIT_LOG:Common_Config::COIN_LOG,$ssotoken,$schoolId);
                    if($insertBought<0)
                        $this->displayJsonUdo(Common_Error::ERROR_SUCCESS,array("result"=>$insertBought));
                }
                //print_r($result);
                $this->displayJsonUdo(Common_Error::ERROR_SUCCESS,array_merge(array("result"=>Common_Error::ERROR_ORDER_SUCCESS),$result));
                break;
            case 2:
                if(array_key_exists("invoke",$result) && array_key_exists("transNo",$result) && $result['transNo'] && $result['invoke']){
                    $accountModel->updateOrder($order,$result['transNo'],Common_Config::ORDER_NOT_PAY);
                    $this->displayJsonUdo(Common_Error::ERROR_SUCCESS,array_merge(array("result"=>Common_Error::ERROR_ORDER_SUCCESS),array("transNo"=>$result['transNo'],"invoke"=>$result['invoke'])));
                }
                break;
        }
        $this->displayJsonUdo(Common_Error::ERROR_FAIL);
    }

    /*
     * 测试上述的支付函数
     */
    function testPayAction(){
        $url = "http://182.92.118.115:8080/Account/pay/";
        $ssotoken = "token6f610c8a-00ef-48fb-bc36-c4205942d58crzNie4gf";
        //$ssotoken = ""
        $schoolId = 10;
        $payType = 1;
        $amt = 100;
        $resource = [];
        array_push($resource,array("resourceType"=>2,"resourceId"=>350));
        array_push($resource,array("resourceType"=>2,"resourceId"=>352));
        array_push($resource,array("resourceType"=>2,"resourceId"=>354));
        array_push($resource,array("resourceType"=>2,"resourceId"=>356));
        array_push($resource,array("resourceType"=>2,"resourceId"=>358));
        array_push($resource,array("resourceType"=>2,"resourceId"=>360));
        array_push($resource,array("resourceType"=>2,"resourceId"=>362));
        array_push($resource,array("resourceType"=>2,"resourceId"=>364));
        array_push($resource,array("resourceType"=>2,"resourceId"=>366));
        array_push($resource,array("resourceType"=>2,"resourceId"=>368));
        //array_push($resource,array("resourceType"=>2,"resourceId"=>1561));

        $json = json_encode($resource);
        print_r($json);
        $coinId = 1;
        $platform = 1;
        $post_data = array("ssotoken" =>$ssotoken,"payType"=>$payType,"amt"=>$amt,"resource"=>$json,"platform"=>$platform,"coinId"=>$coinId,"schoolId"=>$schoolId);
        $cl= new Common_Curl();
        $array = $cl->request($url,$post_data);
        print_r($array);
        $this->displayJsonUdo(Common_Error::ERROR_SUCCESS,$array);
    }

    /*
     * 获取交易结果
     * 交易结果由SSO调用通知
     */
    function getPayResultAction(){

        //接收SSOpost过来的数据
        $request = $this->getRequest();
        if('POST' == $request->getMethod()) {
            $osid = $this->post()->get("osid");
            $transNo = $this->post()->get("transNo");
            $status = $this->post()->get("status");
            $random = $this->post()->get("random");
            $notifyTime = $this->post()->get("notifyTime");
            $sign = $this->post()->get("sign");
        }

        //isSolid标识sign校验的结果，是否可靠
        $isSolid = Common_Config::NOTIFY_SOLID;
        //参数校验
        //sign校验，看传过来的sign和MD5的结果是否一致
        $signVerify = md5(Common_Config::PAY_OSID.$transNo.$status.$random.$notifyTime.Common_Config::PAY_SECRET);
        if($signVerify!=$sign)
            $isSolid = Common_Config::NOTIFY_NOT_SOLID;
        $accountModel = new AccountModel();
        $result = $accountModel->getPayResult($osid,$transNo,$status,$random,$notifyTime,$sign,$isSolid);
    }
    

    /*
     * 移动端调用，获取支付宝订单支付结果
     */
    function getOrderResultAction(){
        $request = $this->getRequest();
        if('POST' == $request->getMethod())
        {
            $transNo = $this->post()->get("transNo");
            $userId = $this->post()->get("userId");
        }
        else{
            $transNo = $this->get("transNo");
            $userId = $this->get("userId");
        }

        //接收参数判断
        if (!$transNo || !$userId)
            $this->displayJsonUdo(Common_Error::ERROR_PARAM,"","缺少必选参数");

        $accountModel = new AccountModel();
        $result = $accountModel->getOrderResult($userId,$transNo);

        $this->displayJsonUdo(Common_Error::ERROR_SUCCESS,array("result"=>$result));

    }

    /*
     * 获取用户当前可购买的课程列表
     */
    function getAvailableCourseAction(){
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

        if( !$userId || !$domainId )
            $this->displayJsonUdo(Common_Error::ERROR_PARAM);

        $tradeModel = new TradeModel();
        //$parent = $tradeModel->getParentId($localId,$domainId,$localType);

        //$parentId = $parent['parent_id'];
        $result = $tradeModel->getAvailableRes($userId,$domainId);
        //print_r($result);
        if( $result == -1)
            $this->displayJsonUdo(Common_Error::ERROR_NO_AVAILABLE,"","当前条件下没有可购买的课程哦~");
        $this->displayJsonUdo(Common_Error::ERROR_SUCCESS,$result);
    }

    /*
     * 查询用户的订单信息
     */
    function getOrderAction(){
        $userId = (int)$this -> get('userId', 0);
        $mobile = $this -> get('mobile', "");
        $status = (int)$this -> get('status',-1);
        $payType = (int)$this -> get('payType',0);
        $previousId = (int)$this -> get('previousId',0);
        $pageSize = (int)$this-> get('pageSize',20);
        $date = $this -> get('date',"");
        $page = (int)$this->get('page',1);

        $startTime = 0;
        $endTime = 0;
        if($date){
            $startTime = strtotime(substr($date,0,10));
            $endTime = strtotime(substr($date,13,10));
        }

/*        print_r($date);
        print_r(substr($date,0,10)." - ".substr($date,13,10));
        print_r($startTime." - ".$endTime);*/
        $accountModel = new AccountModel();
        $order = $accountModel->getOrder($userId,$mobile,$startTime,$endTime,$status,$payType,$previousId,$page,$pageSize);
        $orderList = $order['orderList'];

        $userModel = new UserModel();
        $schoolModel = new SchoolModel();
        $tradeModel = new TradeModel();

        //对orderList进行处理
        foreach($orderList as $k=>$value){
            $orderList[$k]['mobile'] = $userModel->getUserName($value['userId'])['mobile'];
            switch($value['payType']){
                case Common_Config::UDO_PAYTYPE_COIN:
                    $orderList[$k]['payType'] = "U币购买";
                    break;
                case Common_Config::UDO_PAYTYPE_CREDIT:
                    $orderList[$k]['payType'] = "学分购买";
                    break;
                case Common_Config::UDO_PAYTYPE_RECHARGE:
                    $orderList[$k]['payType'] = "U币充值";
                    break;
            }
            switch($value['status']){
                case Common_Config::ORDER_SUCCESS:
                    $orderList[$k]['status'] = "支付成功";
                    break;
                case Common_Config::ORDER_NOT_PAY:
                    $orderList[$k]['status'] = "尚未支付";
                    break;
                case Common_Config::ORDER_FAIL:
                    $orderList[$k]['status'] = "订单支付失败";
                    break;
                case Common_Config::ORDER_CLOSED:
                    $orderList[$k]['status'] = "订单关闭";
                    break;
        }
            $orderList[$k]['createTime'] = date('Y-m-d H:i:s',$value['createTime']);
            //为order中的每一项赋予序号，便于在模板中的foreach给class赋值
            $orderList[$k]['no'] = $k;
            //处理resource
            //resource取出来是序列化的字符串，首先先反序列化
            if($value['resource']){
                $courseCount = 0;
                $orderList[$k]['resource'] = unserialize($value['resource']);
                $schoolInfo = [];
                foreach ($orderList[$k]['resource'] as $v=>$val){
                    if($val['resourceId']==1)
                        continue;
                    $orderList[$k]['resource'][$v]['resourceName'] = $schoolModel->getSingleCourse($val['resourceId'])['name'];
                    $courseCount++;
                    if(!$schoolInfo)
                        $schoolInfo = $schoolModel->getSchoolByCourse($val['resourceId']);
                }
                $orderList[$k]['courseCount'] = $courseCount;
                $orderList[$k]['schoolName'] = $schoolInfo['customer_name'];
                $orderList[$k]['schoolTitle'] = $schoolInfo['customer_title'];
            }

        }

        /*
         * 计算总页数，确定页码显示.页码显示需要的参数：
         */
        $pageNumber = ceil($order['orderCount']/$pageSize);
        //如果总页数比10小或当前页比6小，那么分页变量是1到分页总数
        if($pageNumber <= 10 )
            $pagination =  range(1,$pageNumber);
            elseif($page<=6&&$pageNumber > 10){
                $pagination =  range(1,10);
            }
        else{
            $pagination = range($page-5,$page+4);
        }
        /*
         * 初始化筛选参数
         */
        $initFilter = array("date"=>$date,"status"=>$status,"payType"=>$payType,"mobile"=>$mobile,"userId"=>$userId,"page"=>$page,"pageNumber"=>$pageNumber,
            "orderCount"=>$order['orderCount']);

        $this->assign('orderList',$orderList);
        $this->assign('status',$tradeModel->getOrderStatus());
        $this->assign('payType',$tradeModel->getOrderPaytype());
        $this->assign('init',$initFilter);
        $this->assign('pagination',$pagination);

    }

    /*
     * 获取用户的账户信息：余额，U币日志，学分日志，已购资源
     */
    function getAccountAction(){

        $userId = (int)$this -> get('userId', 0);
        $mobile = $this -> get('mobile', "");
        $date = $this -> get('date', "");
        $pageBalance = $this->get('pageBalance',1);
        $pageCoin = $this->get('pageCoin',1);
        $pageCredit = $this->get('pageCredit',1);
        $pageBought = $this->get('pageBought',1);
        $pageSize = $this->get('pageSize',20);
        $tab = $this->get('tab',1);

        $startTime = 0;
        $endTime = 0;
        if($date){
            $startTime = strtotime(substr($date,0,10));
            $endTime = strtotime(substr($date,13,10));
        }

        $accountModel = new AccountModel();
        $tradeModel = new TradeModel();
        $publicModel = new PublicModel();

        $account = $accountModel->getBalance($userId,$mobile,0,$pageBalance,$pageSize);
        //print_r($account);
        $coinLog = $accountModel->getTradeInfo($userId,0,$pageSize,$startTime,$endTime,$pageCoin);
        $creditLog = $accountModel->getCreditLog($userId,0,$pageSize,$startTime,$endTime,$pageCredit);
        $boughtList = $accountModel->getBought($userId,0,$pageSize,$startTime,$endTime,$pageBought);

        $pageNumberBalance = ceil($account['balanceCount']/$pageSize);
        $pageNumberCoin = ceil($coinLog['coinCount']/$pageSize);
        $pageNumberCredit = ceil($creditLog['creditCount']/$pageSize);
        $pageNumberBought = ceil($boughtList['boughtCount']/$pageSize);

        $pagiBalance = array("pageBalance"=>$pageBalance,"pageNumberBalance"=>$pageNumberBalance,"pagi"=>$publicModel->pageCal($pageBalance,$pageNumberBalance),"count"=>$account['balanceCount']);
        $pagiCoin = array("pageCoin"=>$pageCoin,"pageNumberCoin"=>$pageNumberCoin,"pagi"=>$publicModel->pageCal($pageCoin,$pageNumberCoin),"count"=>$coinLog['coinCount']);
        $pagiCredit = array("pageCredit"=>$pageCredit,"pageNumberCredit"=>$pageNumberCredit,"pagi"=>$publicModel->pageCal($pageCredit,$pageNumberCredit),"count"=>$creditLog['creditCount']);
        $pagiBought = array("pageBought"=>$pageBought,"pageNumberBought"=>$pageNumberBought,"pagi"=>$publicModel->pageCal($pageBought,$pageNumberBought),"count"=>$boughtList['boughtCount']);

        /*
        * 初始化筛选参数
         * tab:当前tab
        */
        $initFilter = array("date"=>$date,"mobile"=>$mobile,"userId"=>$userId,"tab"=>$tab,"pagiBalance"=>$pagiBalance,
            "pagiCoin"=>$pagiCoin,"pagiCredit"=>$pagiCredit,"pagiBought"=>$pagiBought);
        $this->assign("accountList",$account['balance']);
        $this->assign('coinLogList',$coinLog['coinLog']);
        $this->assign('creditLogList',$creditLog['creditLog']);
        $this->assign('boughtList',$boughtList['bought']);
        $this->assign('init',$initFilter);

    }

    /*
     * 定时任务:如果账户中存在
     */
    function checkUncertainNotifyAction(){
        $tblTrans = new DB_Udo_TransNotify();
        //ignore_user_abort();//关掉浏览器，PHP脚本也可以继续执行.
        set_time_limit(0);// 通过set_time_limit(0)可以让程序无限制的执行下去
        $interval=5;// 每隔半小时运行
        $isSolid = Common_Config::NOTIFY_UNCERTAIN;
        $now = time();
        $diff = 60*60;
        //do{
            $result = $tblTrans->fetchAll("uid,transNo,createTime","where isSolid = {$isSolid} and ({$now}-createTime)< {$diff}");
            //$result = $tblTrans->scalar("*","where isSolid = {$isSolid} and ({$now}-createTime)< {$diff}");




            //这里是你要执行的代码
            //sleep($interval);// 等待5分钟
        //}while(true);
}
}


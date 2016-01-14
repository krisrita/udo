<?php
/**
 * Created by PhpStorm.
 * User: Kris
 * Date: 2015/12/10
 * Time: 21:45
 */

/**
 * 账户业务模型
 * @author lijiannan@howdo.cc
 */
class AccountModel
{
    /*
     * 获取用户的账户余额
     */
    function getBalance($uid=0,$mobile = "",$previousId=0,$page=1,$pageSize=20){
        $tblAccount = new DB_Pay_Account();
        $tblPay = new DB_Pay();
        $userModel = new UserModel();
        $where = "where status=0";
        if($uid)
            $where .= " and sso_id = {$uid}";
        if($previousId)
            $where .= " and id<{$previousId}";

       // if(!$mobile){
            $balance = $tblAccount->fetchLimit("sso_id,user_name,score,amt,created_time",$where,"order by id desc",$page,$pageSize);
            $balanceCount = $tblAccount->queryCount($where);
            foreach($balance as $k=>$val){
                $balance[$k]['mobile'] = $userModel->getUserName($val['sso_id'])['mobile'];
            }
            $balance = array("balance"=>$balance,"balanceCount"=>$balanceCount);
       // }
/*        else{
            $balance = $tblAccount->fetchAll("sso_id,user_name,score,amt,created_time",$where,"order by id desc");
            $result = [];
            foreach($balance as $k=>$val){
                $balance[$k]['mobile'] = $userModel->getUserName($val['sso_id'])['mobile'];
                if(strstr($balance[$k]['mobile'],$mobile)!=""){
                    array_push($result,$balance[$k]);
                }
            }
            $balanceCount = count($result);
            //用数组的方式分页
            $balance = array("balance"=>array_slice($result,($page-1)*20,$pageSize),"balanceCount"=>$balanceCount);
        }*/


        //没有查询到余额信息，返回-1
        if(!$balance)
            return -1;

        //如果有mobile查询条件，返回result
        return $balance;
    }

    function getSsoBalance($ssotoken){
        $rand = new Common_Char();
        $random = $rand->getRandChar(8);
        $url = Common_Config::ACCOUNT_QUERY;
        $post_data = array("osid"=>Common_Config::PAY_OSID,"ssotoken"=>$ssotoken,"random"=>$random,"sign"=>md5(Common_Config::PAY_OSID.$ssotoken.$random.Common_Config::PAY_SECRET));
        $cl = new Common_Curl();
        $array = $cl->request($url, $post_data);
        return $array;

    }

    /*
     * 获取U币和人民币的兑换信息
     */
    function getCoinInfo(){
        $tblCoinInfo = new DB_Udo_CoinInfo();
        $coinInfo = $tblCoinInfo->fetchAll("*","where isValid = 1");
        //print_r($coinInfo);
        return $coinInfo;
    }

    /*
     * 获取U币和学分的兑换信息
     */
    function getCreditInfo($uid=0){
        $tblCreditInfo = new DB_Udo_CreditInfo();
        $creditInfo = $tblCreditInfo->fetchAll("*","where isValid = 1");
        //print_r($creditInfo);
        $isEnough = 0;

        if($uid){
            $balance = $this->getBalance($uid)['balance'][0];
            foreach ($creditInfo as $k=>$value){
                if ($balance['amt']>=$value['price'])
                    $isEnough = 1;
                else
                    $isEnough = 0;
                $creditInfo[$k] = array_merge($creditInfo[$k],array("isEnough"=>$isEnough));
            }
        }

        return $creditInfo;
    }

    /*
     * 用户用U币兑换学分
     */
    function coinScore($id,$uid){
        $tblCoinLog = new DB_Udo_CoinLog();
        $tblCreditLog = new DB_Udo_UserCreditLog();
        $tblCreditInfo = new DB_Udo_CreditInfo();
        $tblAccount = new DB_Pay_Account();
        //先找到兑换的汇率和额度
        $creditInfo = $tblCreditInfo->scalar("id,amt,price","where isValid = 1 and id = {$id}");

        //首先更新账户信息
        //获取账户余额
        $id = $tblAccount->scalar("id,amt,score","where sso_id = {$uid}");

        if($id['amt']<$creditInfo['price'])
            return Common_Error::ERROR_SHORT_BALANCE;
        //print_r($id);

        //更新账户信息，如果失败再重试三次
        $retry = 0;
        $updateAccount = 0;
        while(!$updateAccount && $retry<=3){
            $amt = $id['amt']-$creditInfo['price'];
            $score = $id['score']+$creditInfo['amt'];
            $updateAccount = $tblAccount->query("update account set amt = {$amt},score = {$score} where id = {$id['id']}");
            //$updateAccount = $tblAccount->update($id['id'],array("amt"=>$id['amt']-$creditInfo['price'],"score"=>$id['score']+$creditInfo['amt']));
            $retry ++;
        }
        //账户更新失败返回失败信息
        if(!$updateAccount)
            return Common_Error::ERROR_UPDATE_BALANCE;

        //账户更新成功，插入学分和U币变动日支
        $tblCoinLog->insert(array("userId"=>$uid,"amt"=>(0-$creditInfo['price']),"info"=>"U币兑换学分","createTime"=>time()));
        $tblCreditLog->insert(array("userId"=>$uid,"creditSource"=>Common_Config::CREDIT_COIN_EXCHANGE,"info"=>"U币兑换获得学分",
        "amt"=>$creditInfo['amt'],"status"=>Common_Config::CREDIT_SUCCESS,"createTime"=>time()));

        return Common_Error::ERROR_SUCCESS;
    }

    /*
     * 更新账户的学分和余额值
     * param :
     * @score: 更新后的score;@amt: 更新后的U币余额; @id:更新的id
     * 暂未启用
     */
    function updateAccount($score,$amt,$id){
        $tblAccount = new DB_Pay_Account();
        //首先更新账户信息

        //更新账户信息，如果失败再重试三次
        $retry = 0;
        $updateAccount = 0;
        while(!$updateAccount && $retry<=3){
            $updateAccount = $tblAccount->query("update account set amt = {$amt},score = {$score} where id = {$id['id']}");
            //$updateAccount = $tblAccount->update($id['id'],array("amt"=>$id['amt']-$creditInfo['price'],"score"=>$id['score']+$creditInfo['amt']));
            $retry ++;
        }
        //账户更新失败返回失败信息
        if(!$updateAccount)
            return Common_Error::ERROR_UPDATE_BALANCE;
    }

    /*
     * 获取用户所有已购课程
     */
    function getBought($uid=0,$previousId=0,$pageSize=20,$startTime=0,$endTime=0,$page=1){
        $tblBought = new DB_Udo_UserBought();
        $tblResource = new DB_Sso_Resource();
        $tblEntrance = new DB_Sso_Entrance();
        $tblSta = new DB_Udo_SchoolStatistics();
        $tradeModel = new TradeModel();
        $userModel = new UserModel();

        if(!$previousId)
            $previousId = 0;

        $where = "where resourceType = 2";
        if($uid)
            $where .= " and userId = {$uid}";
        if($previousId)
            $where .= " and id<{$previousId}";
        if($startTime || $endTime){
            if(!$endTime)
                $where .= " and createTime >= {$startTime}";
            else
                $where .= " and createTime >={$startTime} and createTime <= {$endTime}";
        }
        $boughtCount = $tblBought->queryCount($where);
        //首先获取用户购买的课程和所在的频道
        $bought = $tblBought->fetchLimit("userId,id,resourceId,schoolId,orderId,createTime",$where,"order by id desc",$page,$pageSize);
        //print_r($bought);

        $newArray = [];
        //接下来对逐个课程，获取频道的具体信息和课程的具体信息
        foreach($bought as $k=>$value){
            $entrance = $tblEntrance->scalar("customer_name,customer_title,logo,api_udo_url","where id = {$value['schoolId']}");
            $sta = $tblSta->queryCount("where schoolId = {$value['schoolId']} group by userId");
            if(!$sta)
                $sta = 0;
            $info = "共有".$sta."位小伙伴与你共同学习";
            $resource = $tblResource->scalar("name","where id = {$value['resourceId']}");
            //获取课程的localId供在列表中进行跳转
            $localId = $tradeModel->getLocalId($value['resourceId'],$value['schoolId']);

            $value['mobile'] = $userModel->getUserName($value['userId'])['mobile'];
            $value['userName'] = $userModel->getUserName($value['userId'])['name'];
            $newArray[$k] = array("listId"=>$value['id'],"id"=>$value['resourceId'],"userId"=>$value['userId'],"mobile"=>$value['mobile'],"userName"=>$value['userName'],"localId"=>$localId['local_id'],"logo"=>$entrance['logo'],"name"=>$resource['name'],"schoolName"=>$entrance['customer_name']
            ,"schoolTitle"=>$entrance['customer_title'],"info"=>$info,"schoolId"=>$value['schoolId'],"apiUdoUrl"=>$entrance['api_udo_url'],"courseType"=>0,
                "orderId"=>$value['orderId'],"createTime"=>date("Y-m-d H:i:s",$value['createTime']));
        }
        return array("bought"=>$newArray,"boughtCount"=>$boughtCount);
    }

    /*
     * 已购课程搜索
     */
    function searchBought($uid,$key){
        //$key = "金";
        $tblBought = new DB_Udo_UserBought();
        $tblResource = new DB_Sso_Resource();
        $tblEntrance = new DB_Sso_Entrance();
        $tblSta = new DB_Udo_SchoolStatistics();
        $tradeModel = new TradeModel();

        //首先获取用户购买的课程和所在的频道
        $bought = $tblBought->fetchAll("id,resourceId,schoolId","where userId ={$uid} and resourceType = 2 ","order by id asc");
        //print_r($bought);
        //print_r($bought);

        $resultArray = [];
        //接下来对逐个课程，获取频道的具体信息和课程的具体信息
        foreach($bought as $k=>$value){
            $entrance = $tblEntrance->scalar("customer_name,customer_title,logo,api_udo_url","where id = {$value['schoolId']} and (customer_name like '%{$key}%'
            or customer_title like '%{$key}%')");
            $resource = $tblResource->scalar("name","where id = {$value['resourceId']} and name like '%{$key}%'");

            //如果搜索到了频道或课程包含关键词，再显示该条信息
            if($entrance || $resource){
                $sta = $tblSta->queryCount("where schoolId = {$value['schoolId']} group by userId");
                if(!$sta)
                    $sta = 0;
                $entrance = $tblEntrance->scalar("customer_name,customer_title,logo,api_udo_url","where id = {$value['schoolId']}");
                $resource = $tblResource->scalar("name","where id = {$value['resourceId']} ");
                $info = "共有".$sta."位小伙伴与你共同学习";
                //获取课程的localId供在列表中进行跳转
                $localId = $tradeModel->getLocalId($value['resourceId'],$value['schoolId']);
                array_push($resultArray,array("id"=>$value['resourceId'],"localId"=>$localId['local_id'],"logo"=>$entrance['logo'],"name"=>$resource['name'],"schoolName"=>$entrance['customer_name']
                ,"schoolTitle"=>$entrance['customer_title'],"info"=>$info,"schoolId"=>$value['schoolId'],"apiUdoUrl"=>$entrance['api_udo_url'],"courseType"=>0));
            }

        }
        return $resultArray;
    }

    /*
     *分页获取U币交易日志
     */
    function getTradeInfo($uid=0,$previousId=0,$pageSize=20,$startTime=0,$endTime=0,$page=1){
        $tblCoinLog = new DB_Udo_CoinLog();
        $resultArray = [];
        $userModel = new UserModel();

        //previousId没有提交的时候默认0
        if(!$previousId)
            $previousId = 0;
        //print_r($previousId);

        //获取分页列表时注意按照时间顺序倒序排
        //首条数据的获取规则不是和上条对比，而是直接按照id来排
        $where = "where id>0 ";
        if($uid)
            $where .=" and userId = {$uid} ";
        if($previousId)
            $where .= " and id<{$previousId}";
        if($startTime || $endTime){
            if(!$endTime)
                $where .= " and createTime >= {$startTime}";
            else
                $where .= " and createTime >={$startTime} and createTime <= {$endTime}";
        }
        $coinCount = $tblCoinLog->queryCount($where);
        $coinLog = $tblCoinLog->fetchLimit("id,amt,info,createTime,userId",$where,"order by id desc",$page,$pageSize);

        //如果首次请求，前一个月数据为0
        if($previousId == 0){
            $previousMonth = 0;
        }
        //否则为上一个月的数据
        else{
            $getPrevious = $tblCoinLog->scalar("id,amt,info,createTime","where id = {$previousId}");
            $previousMonth = date('m',$getPrevious['createTime']);
        }

        //对结果列表中的每一条数据，需要与上月数据对比，如果月份不一样，需要在本条数据之前插入月份信息
        $year = date('Y-m',time());
        foreach($coinLog as $k=>$value){

            //非首页请求的第一条数据需要和上一条对比
            $currentMonth = date('m',$value['createTime']);
            if( ($previousMonth && ( $previousMonth != $currentMonth))||!$previousMonth ){
                $currentYear = date('Y-m',$value['createTime']);
                $createTime = $year == $currentYear ? "本月":$currentMonth."月";
                array_push($resultArray,array("id"=>0,"userId"=>0,"name"=>0,"mobile"=>0,"convertedTime"=>$createTime,"amt"=>0,"info"=>"","createTime"=>$createTime,"context"=>""));
            }

            $value['convertedTime'] = date('Y-m-d H:i:s',$value['createTime']);
            $previousMonth = date('m',$value['createTime']);
            $value['createTime'] = date('m-d',$value['createTime']);
            $value['context'] = "";
            $value['name'] = $userModel->getUserName($value['userId'])['name'];
            $value['mobile'] = $userModel->getUserName($value['userId'])['mobile'];
            array_push($resultArray,$value);
        }
        return array("coinLog"=>$resultArray,"coinCount"=>$coinCount);
    }

    /*
 *分页获取学分交易日志
 */
    function getCreditLog($uid=0,$previousId=0,$pageSize=20,$startTime=0,$endTime=0,$page=1){
        $tblCreditLog = new DB_Udo_UserCreditLog();
        $userModel = new UserModel();
        $resultArray = [];

        if(!$previousId)
            $previousId = 0;
        //获取分页列表时注意按照时间顺序倒序排
        //首条数据的获取规则不是和上条对比，而是直接按照id来排
        $where = "where id>0 ";
        if($uid)
            $where .=" and userId = {$uid} ";
        if($previousId)
            $where .= " and id<{$previousId}";
        if($startTime || $endTime){
            if(!$endTime)
                $where .= " and createTime >= {$startTime}";
            else
                $where .= " and createTime >={$startTime} and createTime <= {$endTime}";
        }
        $creditCount = $tblCreditLog->queryCount($where);
        $creditLog = $tblCreditLog->fetchLimit("id,amt,info,createTime,userId",$where,"order by id desc",$page,$pageSize);

        //如果首次请求，前一个月数据为0
        if($previousId == 0){
            $previousMonth = 0;
        }
        //否则为上一个月的数据
        else{
            $getPrevious = $tblCreditLog->scalar("id,amt,info,createTime","where id = {$previousId}");
            $previousMonth = date('m',$getPrevious['createTime']);
        }

        //对结果列表中的每一条数据，需要与上月数据对比，如果月份不一样，需要在本条数据之前插入月份信息
        $year = date('Y-m',time());
        foreach($creditLog as $k=>$value){

            //非首页请求的第一条数据需要和上一条对比
            $currentMonth = date('m',$value['createTime']);
            if( ($previousMonth && ( $previousMonth != $currentMonth))||!$previousMonth ){
                $currentYear = date('Y-m',$value['createTime']);
                $createTime = $year == $currentYear ? "本月":$currentMonth."月";
                array_push($resultArray,array("id"=>0,"userId"=>0,"name"=>0,"mobile"=>0,"convertedTime"=>$createTime,"amt"=>0,"info"=>"","createTime"=>$createTime,"context"=>""));
            }

            $value['convertedTime'] = date('Y-m-d H:i:s',$value['createTime']);
            $previousMonth = date('m',$value['createTime']);
            $value['createTime'] = date('m-d',$value['createTime']);
            $value['context'] = "";
            $value['name'] = $userModel->getUserName($value['userId'])['name'];
            $value['mobile'] = $userModel->getUserName($value['userId'])['mobile'];
            array_push($resultArray,$value);
        }
        return array("creditLog"=>$resultArray,"creditCount"=>$creditCount);
    }

    /*
     * 生成订单
     */
    function newOrder($uid,$schoolId,$courseCount=0,$payType,$resource=[],$coinId=0,$amount,$platform=0,$couponId=0,$couponAmt=0){
        $tblOrder = new DB_Udo_Order();
        $tblSchoolPrice = new DB_Udo_SchoolPrice();
        $tblResource = new DB_Sso_Resource();
        $tblCoinInfo = new DB_Udo_CoinInfo();
        $tblBought = new DB_Udo_UserBought();
        $tradeModel = new TradeModel();
        $correct = 0;
        $schoolPrice = $tblSchoolPrice->scalar("priceType,price","where resourceId = {$schoolId}");
        $balance = $this->getBalance($uid)['balance'][0];
        $userModel = new UserModel();

        $mobile = $userModel->getUserName($uid)['mobile'];

        //生成订单前，首先判断资源定价信息是否有误
        if($payType == Common_Config::UDO_PAYTYPE_COIN || $payType == Common_Config::UDO_PAYTYPE_CREDIT){
        //如果是频道类型,首先获取频道的定价类型和定价

            //根据用户提交过来的参数并不知道是否全部购买了课程
            //$resource 获取所有非免费课程
            $charge = $tblResource->fetchAll("id","where entrance_id = {$schoolId} and type = 6 and price_type <> 3 and enabled = 1");

            $totalPrice = 0;
            foreach($resource as $k=>$val){
                if($val['resourceType'] == 1)
                    continue;
                $resourcePrice = $tblResource->scalar("id,type,entrance_id,price_type,cur_price","where id = {$val['resourceId']}");
                $totalPrice += $resourcePrice['cur_price'];
                if(($payType==Common_Config::UDO_PAYTYPE_COIN && $resourcePrice['price_type']==Common_Config::UDO_PRICETYPE_COIN) ||
                    ($payType==Common_Config::UDO_PAYTYPE_CREDIT && $resourcePrice['price_type']==Common_Config::UDO_PRICETYPE_CREDIT))
                    $correct = 1;
                else
                    return Common_Error::ERROR_COURSE_PRICETYPE;
                //再判断课程用户是否已经购买
                $bought = $tblBought->scalar("id","where userId ={$uid} and resourceId= {$val['resourceId']} and schoolId = {$schoolId}");
                //print_r($bought);
                if($bought)
                    return Common_Error::ERROR_COURSE_BOUGHT;
            }

            //再判断定价值是否正确
            //如果传过来的课程数量就是所有需付费的课程,那么总价是频道定价
            if(count($charge) == $courseCount)
                $totalPrice = $schoolPrice['price'];
            if($amount != $totalPrice)
                return Common_Error::ERROR_COURSE_PRICE;

            //总价核验后，再核验账户余额
            if($totalPrice > ($payType == Common_Config::UDO_PAYTYPE_COIN?$balance['amt']:$balance['score']))
                return Common_Error::ERROR_SHORT_BALANCE;
            //在数据核验准确后，生成订单
            $newOrder = [];
            $retry = 0;

            //如果生成失败会再循环尝试三次
            while($retry<=3 && !$newOrder){
                $newOrder = $tblOrder->insert(array("userId"=>$uid,"mobile"=>$mobile,"payType"=>$payType,"resource"=>serialize($resource),
                    "amount"=>$amount,"createTime"=>time(),"status"=>Common_Config::ORDER_NOT_PAY));
                $retry++;
                //如果第三次仍失败，返回订单创建失败
                if($retry == 3)
                    return Common_Error::ERROR_ORDER_FAIL;
            }
                return $newOrder;
            }

        //其次，对U币充值选项的数值进行校验
        elseif($payType == Common_Config::UDO_PAYTYPE_RECHARGE)
        {
            $coinInfo = $tblCoinInfo->scalar("amt","where id = {$coinId}");
            if($amount != $coinInfo['amt'])
                return Common_Error::ERROR_COIN_INFO;
            //在数据核验准确后，生成订单
            $newOrder = [];
            $retry = 0;
            $coinMoney = $tradeModel->getCoinMoney($coinId);

            //如果生成失败会再循环尝试三次
            while($retry<=3 && !$newOrder){
                $newOrder = $tblOrder->insert(array("userId"=>$uid,"mobile"=>$mobile,"payType"=>$payType,"coinId"=>$coinId,"money"=>$coinMoney['price'],
                    "amount"=>$amount,"platform"=>$platform,"createTime"=>time(),"status"=>Common_Config::ORDER_NOT_PAY));
                $retry++;
                //如果第三次仍失败，返回订单创建失败
                if($retry == 3)
                    return Common_Error::ERROR_ORDER_FAIL;
            }
            return $newOrder;
        }
    }
    /*
     * 支付
     */
    function pay($ssotoken,$type,$subject,$amt,$chargeAmt,$score,$balanceAmt,$channel,$notifyUrl,$remark){
        $sign = md5(Common_Config::PAY_OSID.$ssotoken.$type.$subject.$amt.$chargeAmt.$score.$balanceAmt.$channel.$notifyUrl.$remark.Common_Config::PAY_SECRET);
        $url = Common_Config::UDO_PAY_SERVICE;
        $post_data = array("osid"=>Common_Config::PAY_OSID,"ssotoken"=>$ssotoken,"type"=>$type,"subject"=>$subject,"amt"=>$amt,"chargeAmt"=>$chargeAmt,
            "score"=>$score,"balanceAmt"=>$balanceAmt,"channel"=>$channel,"notifyUrl"=>$notifyUrl,"remark"=>$remark,"sign"=>$sign);
        $cl = new Common_Curl();
        $array = $cl->request($url, $post_data);
        //print_r($array);
        return $array;
    }

    /*
     * 余额支付，当学分或U币支付成功后，将购买的资源插入购买的数据表中
     */
    function insertBought($uid,$schoolId,$resource,$order,$transNo){
        $tblUserBought = new DB_Udo_UserBought();
        $tblOrder = new DB_Udo_Order();

        //首先更新订单的transNo，便于查询
        $statusSuccess = Common_Config::ORDER_SUCCESS;
        $tblOrder->query("update udo_order set transNo = '{$transNo}',status = {$statusSuccess} where id = {$order}");
        //$tblOrder->update($order,array("transNo"=>$transNo,"status"=>Common_Config::ORDER_SUCCESS));

        //每条资源逐一插入购买列表中
        foreach ($resource as $k=>$val){
            $retry = 0;
            if($val['resourceType'] == 2){
                $insert = 0;
                //如果插入失败会再循环尝试三次
                while($retry<=3 && !$insert) {
                    $insert = $tblUserBought->insert(array("userId" => $uid, "resourceId" =>$val['resourceId'],"schoolId"=>$schoolId,
                        "orderId"=>$order,"createTime"=>time(),"resourceType"=>2));
                    //print_r($insert);
                    $retry++;
                    //如果第三次仍失败，返回购买失败
                    if ($retry == 3)
                         return Common_Error::ERROR_FAIL_BOUGHT;
                    }
            }
        }
        return Common_Error::ERROR_BOUGHT_SUCCESS;

    }

    /*
     * U币或学分购买资源
     * 将U币或学分的增减插入数据表中
     * ssotoken 和schoolId在插入资源购买日志时是必选参数，但在插入U币充值记录时不是必选参数
     */
    function insertTransLog($uid,$amount,$info,$type,$ssotoken="",$schoolId=0){
        //print_r($type);
        //print_r($amount);
        $tblCoinLog = new DB_Udo_CoinLog();
        $tblCreditLog = new DB_Udo_UserCreditLog();
        $isAuth = 0;
        if($ssotoken && $schoolId){
            $userModel = new UserModel();
            $isAuth = $userModel->getUserAuth($uid,$schoolId,$ssotoken);
        }

        //print_r($isAuth);
        switch($type){
            case Common_Config::COIN_LOG:
                $tblCoinLog->insert(array("userId"=>$uid,"amt"=>$amount,"info"=>$info,"createTime"=>time(),"isAuthorized"=>$isAuth));
                break;
            case Common_Config::CREDIT_LOG:
                $tblCreditLog->insert(array("userId"=>$uid,"creditSource"=>Common_Config::CREDIT_BUY,"info"=>$info,
                    "amt"=>$amount,"status"=>Common_Config::CREDIT_SUCCESS,"createTime"=>time(),"isAuthorized"=>$isAuth));
                break;
        }
    }

    /*
     * 更新order订单内容
     */
    function updateOrder($order,$transNo,$status){
        $tblOrder = new DB_Udo_Order();

        //首先更新订单的transNo，便于查询
        $tblOrder->update($order,array("transNo"=>$transNo,"status"=>$status));
        $tblOrder->query("update udo_order set transNo = '{$transNo}' ,status = {$status} where id = {$order}");
    }
    /*
     * 插入支付结果
     */
    function getPayResult($osid,$transNo,$status,$random,$notifyTime,$sign,$isSolid){
        $tblTrans = new DB_Udo_TransNotify();
        $tblOrder = new DB_Udo_Order();
        //获取到该交易对应的uid
        $uid = $tblOrder->scalar("userId","where transNo = {$transNo}");

        $solid = Common_Config::NOTIFY_SOLID;
        //判断当前通知是否已经插入过，且状态是成功，如果是，那么忽略；如果不是，判断状态是否改变，如果是，更新已经插入的记录
        $trans = $tblTrans->scalar("transNo,status","where transNo = '{$transNo}' and isSolid = {$solid}","order by id desc");
        if( $trans ){
            if($trans['status'] == Common_Config::ORDER_SUCCESS)
                return 1;
            else{
                if($status == $trans['status'])
                    return 1;
                else {
                    $update = $tblTrans->query("update udo_trans_notify set status = {$status} where id = {$trans}");
                    return 1;
                }
            }
        }
        else{
            $insert = $tblTrans->insert(array("osid"=>$osid,"uid"=>$uid['userId'],"transNo"=>$transNo,"status"=>$status,"random"=>$random,"notifyTime"=>$notifyTime,"sign"=>$sign
            ,"isSolid"=>$isSolid,"createTime"=>time()));
            return 1;
        }

    }

    /*
     * 客户端获取支付结果
     */
    function getOrderResult($uid,$transNo){
        $tblTrans = new DB_Udo_TransNotify();
        $isSolid = Common_Config::NOTIFY_SOLID;

        //计算超时时间
        //其实计算点
        $startMicroTime = time();
        do{
            $result = $tblTrans->scalar("statusValue","where transNo = {$transNo} and isSolid = $isSolid");
            //终止计算点
            $endMicroTime = time();
            //时间间隔
            $interval = $endMicroTime - $startMicroTime;
        }while( !$result && $interval<3);

        //如果timeout结束还没有接到通知，去公共云主动查询，如果查询没有结果返回失败状态
        if(!$result){
            $rand = new Common_Char();
            $random = $rand->getRandChar(8);

            $sign = md5(Common_Config::PAY_OSID.$transNo.$random.Common_Config::PAY_SECRET);
            $post_data = array("osid"=>Common_Config::PAY_OSID,"transNo"=>$transNo,"random"=>$random,"sign"=>$sign);
            $url = Common_Config::TRANS_QUERY;
            $cl = new Common_Curl();
            $result = $cl->request($url, $post_data);

            if(!$result)
                return Common_Error::ERROR_TRANS_UNKNOWN;

        }
        //$result['status'] = 0;

        //如果查到了交易的信息，判断交易的状态
        $notifyUncertain = Common_Config::NOTIFY_UNCERTAIN;
        $updateId = $tblTrans->scalar("id","where transNo = '{$transNo}' and isSolid = {$notifyUncertain}");
        //print_r($result);
        switch($result['statusValue']){
            case Common_Config::ORDER_SUCCESS:
                //如果获取到了支付成功的通知信息，更新U币交易日志和order的状态
                //先从order表获取交易的coinId
                $tblOrder = new DB_Udo_Order();
                $tblCoinInfo = new DB_Udo_CoinInfo();
                $coin = $tblOrder->scalar("id,coinId","where transNo = '{$transNo}'");
                $coinId = $coin['coinId'];
                $coinInfo = $tblCoinInfo->scalar("amt,price","where id = {$coinId}");
                $info = "充值".$coinInfo['amt']."U币";
                //将U币充值信息写入coinLog
                $this->insertTransLog($uid,$coinInfo['amt'],$info,Common_Config::COIN_LOG);
                //$tblOrder->update($coin['id'],array("status"=>Common_Config::ORDER_SUCCESS));
                $statusSuccess = Common_Config::ORDER_SUCCESS;
                $tblOrder->query("update udo_order set status = {$statusSuccess} where id = {$coin['id']}");

                //更新通知的状态为可靠
                if($updateId){
                    $notifySolid = Common_Config::NOTIFY_SOLID;
                    $tblTrans->query("update udo_trans_notify set isSolid = {$notifySolid} where id = {$updateId['id']}");
                    //$tblTrans->update($updateId['id'],array("isSolid"=>Common_Config::NOTIFY_SOLID));
                }

                return Common_Error::ERROR_ORDER_SUCCESS;
            //如果订单是未支付的状态，那么将订单信息写入transNotify
            case Common_Config::ORDER_NOT_PAY:
                //如果没有写入，则写入信息
                if(!$updateId)
                    $tblTrans->insert(array("transNo"=>$transNo,"isSolid"=>Common_Config::NOTIFY_UNCERTAIN,"createTime"=>time()));
                return Common_Error::ERROR_TRANS_UNKNOWN;
            //如果接收到订单失败的消息，
            case Common_Config::ORDER_FAIL:
                if($updateId){
                    $notifySolid = Common_Config::NOTIFY_SOLID;
                    $tblTrans->query("update udo_trans_notify set isSolid = {$notifySolid} where id = {$updateId['id']}");
                    //$tblTrans->update($updateId['id'],array("isSolid"=>Common_Config::NOTIFY_SOLID));
                }
                return Common_Error::ERROR_TRANS_FAIL;
            case Common_Config::ORDER_CLOSED:
                if($updateId){
                    $notifySolid = Common_Config::NOTIFY_SOLID;
                    $tblTrans->query("update udo_trans_notify set isSolid = {$notifySolid} where id = {$updateId['id']}");
                    //$tblTrans->update($updateId['id'],array("isSolid"=>Common_Config::NOTIFY_SOLID));
                }
                return Common_Error::ERROR_TRANS_CLOSED;
            default:
                return Common_Error::ERROR_TRANS_UNKNOWN;
        }
    }

    /*
     * 检查通知表中是否存在限时内未确认的交易状态：如果存在，需要再去获取一遍交易状态.如果状态改变，需要向客户端推送消息；
     * 如果不存在，需要查超出时限的是否还有未确认的，如有，给用户推送通知，然后将isSolid置为可靠
     */
    function checkUncertainNotify(){
        $tblTrans = new DB_Udo_TransNotify();
        $messageModel = new MessageModel();
        //ignore_user_abort();//关掉浏览器，PHP脚本也可以继续执行.
        set_time_limit(0);// 通过set_time_limit(0)可以让程序无限制的执行下去
        $interval=5;// 每隔半小时运行
        $isSolid = Common_Config::NOTIFY_UNCERTAIN;
        $now = time();
        $diff = 60*60;
        do{

            //查询是否存在限时内未确认的交易状态
            $result = $tblTrans->fetchAll("uid,transNo,createTime","where isSolid = {$isSolid} and ({$now}-createTime)<= {$diff}");
            if($result){
                $userArray = [];
                foreach($result as $k=>$value){
                    $notify = $this->getOrderResult($value['uid'],$value['transNo']);
                    //如果存在状态改变的，就将接收消息的用户列表中插入一条数据
                    if( $notify!=Common_Error::ERROR_TRANS_UNKNOWN){
                        array_push($userArray,$value['uid']);
                    }
                }
                //如果有接收用户，那么发送消息
                if($userArray){
                    $type = 1;
                    $custom_data = "";
                    $title = "支付状态确认";
                    $text = "您有一笔订单的交易状态已经确认";
                    $messageModel->sendMessage($type,$custom_data,$userArray,$custom_data,$title,$text);
                }
            }
            else{
                $result = $tblTrans->fetchAll("id,uid,transNo,createTime","where isSolid = {$isSolid} and ({$now}-createTime)> {$diff}");
                $userArray = [];
                foreach ($result as $k=>$value){
                    array_push($userArray,$value['id']);
                    $notifySolid = Common_Config::NOTIFY_SOLID;
                    //$update = $tblTrans->update("{$value['id']}",array("transNo = {$notifySolid}"));
                    $update = $tblTrans->query("update udo_trans_notify set isSolid = {$notifySolid} where id = {$value['id']}");
                }

                //如果有接收用户，那么发送消息
                if($userArray){
                    $type = 1;
                    $custom_data = "";
                    $title = "支付状态确认";
                    $text = "您有一笔订单的交易已经关闭";
                    $messageModel->sendMessage($type,$custom_data,$userArray,$custom_data,$title,$text);
                }
            }

        //$result = $tblTrans->scalar("*","where isSolid = {$isSolid} and ({$now}-createTime)< {$diff}");

        //这里是你要执行的代码
        //sleep($interval);// 等待5分钟
        }while(true);
    }

    /*
     * 支付中的行为记录
     */
    function orderLog($uid,$type,$pos,$resource,$action){
        $tblPayAction = new DB_Udo_PayActionLog();
        $insert = $tblPayAction->insert(array("userId"=>$uid,"type"=>$type,"pos"=>$pos,"resource"=>$resource,"action"=>$action));
        return $insert;
    }

    /*
     * 条件查询订单信息
     */
    function getOrder($userId=0,$mobile="",$startTime=0,$endTime=0,$status=0,$payType=0,$previousId=0,$page=1,$pageSize=10){
        $tblOrder = new DB_Udo_Order();

        $where = "where id>0 ";
        $lastCreateTime = $tblOrder->scalar("createTime","where id = {$previousId}");
        $where .= $previousId?" and createTime < {$lastCreateTime['createTime']}":"";
        if($userId)
            $where .=" and userId = {$userId}";
        if($mobile)
            $where .= " and mobile like '%{$mobile}%'";
        if($startTime || $endTime){
            if(!$endTime)
                $where .= " and createTime >= {$startTime}";
            else
                $where .= " and createTime >={$startTime} and createTime <= {$endTime}";
        }
        if($status>=0)
            $where .= " and status = {$status}";
        if($payType>0)
            $where .= " and payType = {$payType}";

        //$orderList = $tblOrder->fetchAll("*",$where);
        $orderList = $tblOrder->fetchLimit("*",$where,"order by createTime asc",$page,$pageSize);
        $orderCount = $tblOrder->queryCount($where);
        return array("orderList"=>$orderList,"orderCount"=>$orderCount);
    }

}
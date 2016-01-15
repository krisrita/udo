<?php
/**
 * Created by PhpStorm.
 * User: Kris
 * Date: 2015/11/6
 * Time: 20:16
 */


/**
 * 积分业务模型
 * @author lijiannan@howdo.cc
 */
class CreditModel
{
    function getCredit1($userId,$actionId,$userAuth){
       $tblCredit = new DB_Udo_CreditAction();
        $tblLog = new DB_Udo_UserCreditLog();
        //获取该动作的基础信息
        $result = $tblCredit -> scalar("creditAmount,outputInput,ratio,times,isSynthesized,actionType","where actionId = {$actionId}");

        //print_r($result);

        //开始判断第一种情况，该操作是综合类产出
        if($result['isSynthesized']){
            //找到该操作的所有同类操作
            $sameAction = $tblCredit -> fetchAll("actionId","where actionType = {$result['actionType']}");
            //print_r($sameAction);

            //从用户积分日志表中找到同类操作的次数
            $strAction = "(";

            foreach($sameAction as $k=>$value){
                $str = (string)$value['actionId'];
                $strAction .= $str;
                if (array_key_exists($k+1,$sameAction))
                    $strAction .= ",";
            }
            $strAction .= ")";


            //获取积分日志中该类别综合产出的次数
            $today = strtotime("today");
            $tomorrow = strtotime("tomorrow");
            //失败的记录之后如果重新插入成功，额外多加一条成功日志而不是更新失败日志
            $actionLog = $tblLog -> fetchAll("id,userId,actionId,status,createTime","where actionId in {$strAction} and createTime between {$today} and {$tomorrow}
             and status <> 0");

            //$times:增加积分的次数；$save：目前在log日志中存储的尚未增加积分的和当前操作不同的同类操作
            $times = $tblLog -> queryCount("where actionId= {$actionId} and createTime between {$today} and {$tomorrow}
             and status = 1");
            $save = $tblLog -> fetchAll("actionId,count(*)","where actionId in {$strAction} and actionId <> {$actionId} and createTime between {$today} and {$tomorrow}
             and status = 3 group by actionId");

/*            print_r($times);
            print_r($result['times']);
            print_r($save);*/
            //如果综合产出奖励积分的次数已达上限
            if($times >= $result['times']){
                $insertArray = array("userId"=>$userId,"creditSource"=>0,"actionId"=>$actionId,
                    "creditAmount"=>0,"status"=>3,"isAuthorized"=>$userAuth,"createTime"=>time());
                $tblLog -> insert($insertArray);
                return array("creditAmount" => 0,"isEnough"=>1,"text"=>"上限");
                //写入用户行为日志，积分操作已达上限

            }
            //如果综合产出奖励积分的次数未达上限，且就差一个达到要求
            if($times < $result['times'] && (count($sameAction) - count($save))==1){
                //更新日志中显示未加积分的操作的状态
                foreach ($save as $k => $val){
                    $id = $tblLog -> scalar("id","where actionId = {$val['actionId']} and createTime between {$today} and {$tomorrow}
             and status = 3");
                    $update = $tblLog ->update($id['id'],array("status"=>1));
                }
                $insertArray = array("userId"=>$userId,"creditSource"=>0,"actionId"=>$actionId,
                    "creditAmount"=>0,"status"=>1,"isAuthorized"=>$userAuth,"createTime"=>time());
                $insert = $tblLog -> insert($insertArray);

                //在用户账户里增加相应积分

                //返回积分增加信息
                return array("creditAmount" => $result['creditAmount'],"isEnough"=>1,"text"=>"+{$result['creditAmount']}积分");
            }

            //否则，在日志表中插入一条新记录
            $insertArray = array("userId"=>$userId,"creditSource"=>0,"actionId"=>$actionId,
                "creditAmount"=>0,"status"=>3,"isAuthorized"=>$userAuth,"createTime"=>time());
            $tblLog -> insert($insertArray);
            return array("creditAmount" => 0,"isEnough"=>1,"text"=>"");

        }
        //结束综合产出的情况

        //如果是消耗积分

        //从

        //测试返回：
        return array("creditAmount" => 3,"isEnough"=>1,"text"=>"+3积分!");
    }

    //获取用户的行为，计算相应的学分并返回给云滴
    function getCredit($userId,$actionId,$schoolId){
        $tblCredit = new DB_Udo_CreditAction();
        $tblAccount = new DB_Pay_Account();
        $tblCreditLog = new DB_Udo_UserCreditLog();
        $rand = new Common_Char();



        //获取该动作的基础信息
        $result = $tblCredit -> scalar("creditAmount,name,outputInput,ratio,times,isSynthesized,actionType","where actionId = {$actionId} and isValid = 1");
        if(!$result)
            return Common_Error::ERROR_ACTION;
        $upTimes = $result['times'];

        //接下来获取用户当日的同action的操作
        $today = strtotime("today");
        $tomorrow = strtotime("tomorrow");
        $statusSuccess = Common_Config::CREDIT_SUCCESS;
        $sameTimes = $tblCreditLog -> queryCount("where actionId = {$actionId} and userId = {$userId} and createTime between {$today} and {$tomorrow}
             and status = {$statusSuccess}");

        //如果今日增长已经超出限额
        if($sameTimes>=$upTimes)
            return Common_Error::ERROR_OVER_TIMES;

        //否则更新账户信息，如果失败再重试三次
        $retry = 0;
        $updateAccount = 0;
        $id = $tblAccount->scalar("id,amt,score","where sso_id = {$userId}");
        while(!$updateAccount && $retry<=3){
            $score = $id['score']+$result['creditAmount'];
            //$updateAccount = $tblAccount->query("update account set score = {$score} where id = {$id['id']}");
            $remark = "日常活动";
            $random = $rand->getRandChar(8);
            $sign = md5(Common_Config::PAY_OSID.$userId.$score,$remark,$random.Common_Config::PAY_SECRET);
            $url = Common_Config::UDO_PAY_SERVICE;
            $post_data = array("osid"=>Common_Config::PAY_OSID,"ssoid"=>$userId,"score"=>$result['creditAmount'],"remark"=>$score,"random"=>$random,"sign"=>$sign);
            $cl = new Common_Curl();
            $updateAccount = $cl->request($url, $post_data);
            $retry ++;
        }
        //账户更新失败返回失败信息
        if(!$updateAccount)
            return Common_Error::ERROR_UPDATE_BALANCE;

        //账户更新成功，插入学分和U币变动日支
        $tblCreditLog->insert(array("userId"=>$userId,"creditSource"=>Common_Config::DAILY_CREDIT_ACTION,"actionId"=>$actionId,"info"=>$result['name']."获得学分",
            "amt"=>$result['creditAmount'],"status"=>Common_Config::CREDIT_SUCCESS,"schoolId"=>$schoolId,"createTime"=>time()));
        return Common_Error::ERROR_SUCCESS;

    }
}
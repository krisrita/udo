<?php
/**
 * Created by PhpStorm.
 * User: Kris
 * Date: 2015/11/4
 * Time: 10:57
 * 积分相关接口
 */

/**
 * 课程相关接口
 */
class CreditController extends Base_Contr
{

    function simpleCreditAction(){
        $request = $this->getRequest();
        if('POST' == $request->getMethod())
        {
            $userId = $this->post()->get("userId");
            $actionId = $this->post()->get("actionId");
            $schoolId = $this->post()->get("schoolId");
        }
        else{
            $userId = $this->get("userId");
            $actionId = $this->get("actionId");
            $schoolId = $this->get("schoolId");
        }

        //如果所传参数有空值（无效），json返回错误信息
        if(!$userId || !$actionId )
            $this ->displayJsonUdo(Common_Error::ERROR_PARAM);

        //获取用户权限，用户是否是“私有”频道的授权用户
        /*$userModel = new UserModel();
        $userAuth = $userModel->getUserAuth($userId,$schoolId);*/

        $creditModel = new CreditModel();
        $result = $creditModel->getCredit($userId,$actionId,$schoolId);
        //如果不是
        //if(!$userAuth)
        //print_r($result);

        $this->displayJsonUdo($result);

    }

    function testCreditAction(){
        $request = $this->getRequest();
        if('POST' == $request->getMethod())
        {
            $userId = $this->post()->get("userId");
            $isAnswered = $this->post()->get("isAnswered");
            $answer = $this->post()->get("answer");
            $courseSection = $this->post()->get("courseSection");
            $schoolId = $this->post()->get("schoolId");

        }
        else{
            $userId = $this->get("userId");
            $isAnswered = $this->get("isAnswered");
            $answer = $this->get("answer");
            $courseSection = $this->get("courseSection");
            $schoolId = $this->get("schoolId");
        }

        //如果所传参数有空值（无效），json返回错误信息
        if(!$userId || !$isAnswered ||!$schoolId ||!$courseSection ||!is_array($answer))
            $this ->displayJsonUdo(Common_Error::ERROR_PARAM);

        foreach ($answer as $k=>$val){
            if( !$val['itemId']|| !$val['isCorrect'])
                $this ->displayJsonUdo(Common_Error::ERROR_PARAM);
        }

        $result = array("creditAmount" => 3,"isEnough"=>1,"text"=>"+3积分!");
        $this ->displayJsonUdo(Common_Error::ERROR_SUCCESS,$result);
    }
}
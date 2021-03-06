<?php
/**
 * Created by PhpStorm.
 * User: Kris
 * Date: 2015/12/24
 * Time: 14:51
 */
/*
 * TradeController中完成资费体系中的查询和写入，和个人账户无关
 */
class TradeController extends Base_Contr
{
    public $needLogin = false;

    /*
     * 获取资源定价信息
     */
    public function getPriceAction()
    {
        $priceType = (int)$this -> get('priceType', 0);
        $isPriced = (int)$this -> get('isPriced',2);
        $isPublic = (int)$this -> get('isPublic', 2);
        $keyword = $this -> get('keyword', "");
        $schoolId = (int)$this -> get('schoolId',0);

        $tradeModel = new TradeModel();
        $schoolList = $tradeModel->getSchoolPrice($priceType,$isPriced,$isPublic,$keyword,$schoolId);
        if(!$schoolList)
            $this->redirect("/error/?errno=" . Common_Error::ERROR_SCHOOL_NOT_EXISTS);
        else
            $this->assign('schoolList',$schoolList);

    }

    /*
     *获取单个频道的定价信息
     */
    public function getSchoolPriceAction(){
        $schoolId = (int)$this -> get('schoolId',0);
        $tradeModel = new TradeModel();

        $courseList = $tradeModel->getSchoolCoursePrice($schoolId);
        $school = $tradeModel->getSingleSchoolPrice($schoolId);

        $this->assign('school',$school);
        $this->assign('courseList',$courseList['courseList']);
        $this->assign('resourceNum',$courseList['resourceNum']);

    }

    /*
     * 获取U币和学分的兑换额度
     */
    public function getCoinCreditAction(){
        $accountModel = new AccountModel();
        $tradeModel = new TradeModel();
        $coinInfo = $accountModel->getCoinInfo();
        $creditInfo = $accountModel->getCreditInfo();
        $order = $tradeModel->getTopOrder();
        $this->assign('coinInfo',$coinInfo);
        $this->assign('creditInfo',$creditInfo);
        $this->assign('order',$order);

}
    /*
     *学分规则
     */
    public function getCreditRulesAction(){
        $tradeModel = new TradeModel();
        $creditAction = $tradeModel->getCreditAction();
        $this->assign('creditRule',$creditAction);
    }

    /*
     * 加入新的学分规则
     */
    public function newRuleAction(){
        $actionId = $this->get('actionId');
        $name = $this->get('name');
        $actionType = $this->get('actionType');
        $outputInput = $this->get("io");
        $creditAmount = $this->get('creditAmt');
        $frequency = $this->get('freq');
        $times = $this->get('times');
        $action = $this->get('action');
        $id = $this->get('id');
        $tradeModel = new TradeModel();

        $result = $tradeModel->newRule($actionId,$name,$actionType,$outputInput,$creditAmount,$frequency,$times,$action,$id);
        $this->redirect("getCreditRules");
    }

    /*
     * 删除当前学分规则
     */
    public function deleteRuleAction(){
        $id = (int)$this->get('id');
        $tradeModel = new TradeModel();
        $result = $tradeModel->deleteRule($id);
        return $result;
    }

    /*
     * 删除U币或学分兑换信息
     */
    public function deleteCoinCreditAction(){
        $id = (int)$this->get('id');
        $type = (int)$this->get('type');
        $tradeModel = new TradeModel();
        $result = $tradeModel->deleteCoinCredit($id,$type);
        return $result;
    }

    /*
     * 添加新的兑换信息
     */
    public function newExchangeAction(){
        $amt = $this->get('amt');
        $price = $this->get('price');
        $order = (int)$this->get('order');
        $type = $this->get('type');
        $action = $this->get('action');
        $id = (int)$this->get('id',0);

        $tradeModel = new TradeModel();
        $result = $tradeModel->newExchange($amt,$price,$order,$type,$action,$id);
        $this->redirect("getCoinCredit");
    }

    /*
     * 添加新的频道定价
     */
    public function newSchoolPriceAction(){

        $id = $this->get('id');
        $priceType = $this->get('priceType');
        $discount = $this->get('discount');

        $tradeModel = new TradeModel();
        $result = $tradeModel->newSchoolPrice($id,$priceType,$discount);

        //$this->redirect("/error/?errno=" . Common_Error::ERROR_PARAM);
        $this->redirect("getSchoolPrice?schoolId=".$id);
        //$this->redirect("getPrice");
    }

    /*
     * 添加新的课程定价
     */
    public function newResourcePriceAction(){
        $resourceNum = (int)$this->get('resourceNum');
        $schoolId = (int)$this->get('schoolId');

        $resourcePrice = [];
        for($i=0;$i<=$resourceNum;$i++){
            $priceNotFree = $this->get('priceType'.$i);
            //print_r('priceType'.$i."notFree:".$priceNotFree." ");
            $price = $this->get('price'.$i,0);
            //print_r("price:".$price." ");
            $resourceId = (int)substr($priceNotFree,1);
            $notFree = (int)substr($priceNotFree,0,1);
            array_push($resourcePrice,array("resourceId"=>$resourceId,"notFree"=>$notFree,"price"=>$price));
        }
        //print_r($resourcePrice);
        $tradeModel = new TradeModel();
        $result = $tradeModel->newResourcePrice($resourcePrice,$schoolId);
        $this->redirect("getSchoolPrice?schoolId=".$schoolId);
    }
    }
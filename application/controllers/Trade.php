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
        $this->assign('courseList',$courseList);

    }

    /*
     * 获取U币和学分的兑换额度
     */
    public function getCoinCreditAction(){
        $accountModel = new AccountModel();
        $coinInfo = $accountModel->getCoinInfo();
        $creditInfo = $accountModel->getCreditInfo();

        $this->assign('coinInfo',$coinInfo);
        $this->assign('creditInfo',$creditInfo);

}
    /*
     *学分规则
     */
    public function getCreditRulesAction(){
        $tradeModel = new TradeModel();
        $creditAction = $tradeModel->getCreditAction();
        $this->assign('creditRule',$creditAction);
    }
    }
<?php
/**
 * Created by PhpStorm.
 * User: Kris
 * Date: 2016/1/19
 * Time: 14:04
 */
class ShareController extends Base_Contr
{

    /*
     * 答题报告
     */
    function reportAction(){
        $sectionName = $this->get('sectionName');
        $defeatRate = $this->get('defeatRate');
        $rank = $this->get('rank');
        $title = $this->get('title');

        $this->assign('sectionName',$sectionName);
        $this->assign('defeatRate',$defeatRate);
        $this->assign('rank',$rank);
        $this->assign('title',$title);

    }

    /*
     * 频道分享
     */
    function schoolAction(){
        $name = $this->get('name');
        $title = $this->get('title');
        $logo = $this->get('logo');

        $this->assign('name',$name);
        $this->assign('title',$title);
        $this->assign('logo',$logo);
    }

    /*
     * udo课堂分享
     */
    function shareAction(){

        $request = $this->getRequest();
        if('POST' == $request->getMethod())
        {
            $type = $this->post()->get('type');
            $newsId = $this->post()->get('newsId');
            $schoolId = $this->post()->get('schoolId');
            $courseId = (int)$this->post()->get('courseId');
            $courseName = $this->post()->get('courseName');
            $sectionId = (int)$this->post()->get('sectionId');
            $sectionName = $this->post()->get('sectionName');

            $defeatRate = $this->post()->get('defeatRate');
            $rank = $this->post()->get('rank');
            $title = $this->post()->get('title');
            $uid = $this->post()->get('uid');
            $bannerId = $this->post()->get('bannerId');
        }
        else{
            $type = (int)$this->get('type');
            $newsId = $this->get('newsId');
            $schoolId = (int)$this->get('schoolId');
            $courseId = (int)$this->get('courseId');
            $courseName = $this->get('courseName');
            $sectionId = (int)$this->get('sectionId');
            $sectionName = $this->get('sectionName');

            $defeatRate = $this->get('defeatRate');
            $rank = $this->get('rank');
            $title = $this->get('title');
            $uid = $this->get('uid');
            $bannerId = $this->get('bannerId');
        }

        $schoolModel = new SchoolModel();
        switch($type){
            case Common_Config::SHARE_SCHOOL:
                $school = $schoolModel->getResourceInfo(Common_Config::UDO_RESOURCE_SCHOOL,$schoolId,0,0);
                $title = "听课做题用UDO";
                $info = "我在UDO微课堂看'".$school['customer_name']."'推出的'".$school['customer_title']."'微课，居然一不小心全懂了，快当学霸了好紧张....";
                $url = Common_Config::STATIC_BASE_URL."/share/"."school?name=".$school['customer_name']."&title=".$school['customer_title']."&logo=".$school['logo'];
                $this->displayJsonUdo(Common_Error::ERROR_SUCCESS,array("url"=>$url,"title"=>$title,"intro"=>$info,"logo"=>$school['logo']));
                break;
            case Common_Config::SHARE_REPORT:
                //$this->redirect("report?defeatRate=".$defeatRate."&rank=".$rank."&title=".$title);
                $url = Common_Config::STATIC_BASE_URL."/share/"."report?sectionName=".$sectionName."&defeatRate=".$defeatRate."&rank=".$rank."&title=".$title;
                $logo = $schoolModel->getResourceInfo(Common_Config::PUBLIC_COURSE_TYPE,0,$courseId,$courseName);
                $info = "我在udo微课堂的".$sectionName."测验中排名第".$rank."，求超越！手机答题还能看解析，果然是哪里不会点哪里";
                $title = "听课做题用UDO";
                $this->displayJsonUdo(Common_Error::ERROR_SUCCESS,array("url"=>$url,"title"=>$title,"intro"=>$info,"logo"=>$logo['logo']));
                break;
            case Common_Config::SHARE_COURSE:
                $course = $schoolModel->getResourceInfo(Common_Config::PUBLIC_COURSE_TYPE,0,$courseId,$courseName);
                $info = "我在UDO微课堂看'".$course['customer_name']."'推出的'".$courseName."'微课，居然一不小心全懂了，快当学霸了好紧张....";
                $title = "听课做题用UDO";
                $url = Common_Config::STATIC_BASE_URL."/share/"."school?name=".$course['customer_name']."&title=".$courseName."&logo=".$course['logo'];
                $this->displayJsonUdo(Common_Error::ERROR_SUCCESS,array("url"=>$url,"title"=>$title,"intro"=>$info,"logo"=>$course['logo']));
                break;
            case Common_Config::SHARE_NEWS:
                $news = $schoolModel->getSingleNews($newsId);
                $this->displayJsonUdo(Common_Error::ERROR_SUCCESS,array("url"=>$news['url'],"title"=>$news['title'],"logo"=>$news['logo'],"intro"=>$news['intro']));
                break;
            case Common_Config::SHARE_BANNER:
                $banner = $schoolModel->getSingleBanner($bannerId);
                print_r($banner."banner");
                
                //如果是寒假宣传banner
                if($bannerId == 8){
                $schoolModel = new SchoolModel();
                $count = $schoolModel->bannerData(2780,$uid);
                print_r($count."count");
                $boughtCount = $count['boughtCount'];
                $courseCount = $count['courseCount'];
                $banner['bannerUrl'] = Common_Config::STATIC_BASE_URL."/share/holiday?bCount={$boughtCount}&cCount={$courseCount}";
            }
        
                $this->displayJsonUdo(Common_Error::ERROR_SUCCESS,array("url"=>$banner['bannerUrl'],"title"=>$banner['customerName'],"logo"=>Common_Config::STATIC_BASE_URL.$banner['logo'],"intro"=>$banner['intro']));
                break;
        }

    }


    function holidayAction(){
        $bCount = $this->get('bCount',958);
        $cCount = $this->get('cCount',0);
        $this->assign('bCount',$bCount);
        $this->assign('cCount',$cCount);
    }

    function ruleAction(){

    }
}
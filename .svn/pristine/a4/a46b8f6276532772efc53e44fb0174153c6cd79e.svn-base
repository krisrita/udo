<?php

class PaperController extends Base_Contr
{
    /**
     * 试卷列表
     */
    function listAction()
    {
		$pagesize = 15;
		$courseId = (int)$this -> get('course_id', 0);
		$paperId = (int)$this -> get('paper_id', 0);
		$type = (int)$this -> get('type', 0);

        $courseModel = new CourseModel();
        $course = $courseModel -> getCourse($courseId);
        if(!$course) {            
            $this->redirect("/error/?errno=" . Common_Error::ERROR_COURSE_NOT_EXISTS);
        }

        
        $schoolModel = new SchoolModel();
        $school = $schoolModel -> getSchool($course['school_id']);
        if(!$school) {            
            $this->redirect("/error/?errno=" . Common_Error::ERROR_SCHOOL_NOT_EXISTS);
        }

		$paperModel = new PaperModel();
		//$list = $paperModel -> getList($courseId, $paperId, $type, $pagesize);
        $list = $paperModel -> getAll($courseId);

        if (!$list) {
            $this->redirect("/error/?errno=" . Common_Error::ERROR_COURSE_NOT_EXISTS);
        }

        $courseModel = new CourseModel();
        $practiseList = $courseModel->getCoursePractiseList($courseId);
        $uSecList = $courseModel -> getUserCourseSection($this->uid, $courseId);
        $uSectionIds = $uSecList ? array_keys($uSecList) : array();
        foreach ($list as $k=>$section) {
            $list[$k] = array('id'=>$section['id'], "name" => $section['name']);
            $list[$k]['last'] = isset($uSectionIds[0]) && $section['id'] == $uSectionIds[0] ? 1 : 0;
            $list[$k]['practise_video_list'] = isset($practiseList[$section['id']]) ? $practiseList[$section['id']] : array();
            
            if (empty($list[$k]['practise_video_list'])) {
                $list[$k]['practise_video_list_for_android'] = array();
                continue;
            }

            foreach ($list[$k]['practise_video_list'] as $seq=>$videoId) {
                $list[$k]['practise_video_list_for_android'][] = array("practise_seq"=> $seq, "practise_video_id" => $videoId);
            }

        }

        if ($this->isMobile) {
            $this->displayJson(Common_Error::ERROR_SUCCESS, $list);
        }

        $course['practise_num'] = $courseModel -> getCoursePractiseNum($courseId);
        $course['user_practise_num'] = $courseModel -> getUserCoursePractiseNum($this->uid, $courseId);
        $course['duration_minute'] = round($paperModel->getPaperDuration($courseId)/60, 0);        
        $course['user_spend_minute'] = round($paperModel->getUserPaperDuration($this->uid, $courseId)/60, 0);
		$course['teachers'] = $courseModel->getCourseTeachers($courseId);

        $this->assign("school", $school);
        $this->assign("course", $course);
		$this->assign('paper_list', $list);

    }



}

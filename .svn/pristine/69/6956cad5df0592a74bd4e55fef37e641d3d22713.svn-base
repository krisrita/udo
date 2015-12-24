<?php

class PractiseController extends Base_Section
{

    /**
     * 练习题
     */
    function reportAction()
    {
        $uid = (int)$this->get('uid', $this->uid);
        $practiseModel = new PractiseModel();
        $practise = $practiseModel -> getUserPractiseReport($uid, $this->sectionId);
        
        if($this->isMobile) {
            //没有做过该题
            if (!$practise) {
                $this->displayJson(Common_Error::ERROR_PRACTISE_HAS_NOT_BEEN_DONE);
            }
            $practise['course_name'] = $this->course['name'];
            $practise['chapter_name'] = $this->chapter['name'];
            $practise['chapter_seq'] = $this->chapter['seq'];            
            $practise['section_name'] = $this->section['name'];
            $practise['section_seq'] = $this->section['seq']; 
            $this->displayJson(Common_Error::ERROR_SUCCESS, $practise);
        }


        //没有做过该题
        if (!$practise) {
            header("Location:/course/info/course_id/" . $this->course['id']);
            exit;
        }

        $this->assign("practise", $practise);
        $this->assign("section", $this->section);
        $this->assign("chatper", $this->chapter);
        $this->assign("course", $this->course);
    }



    /**
     * 全部解析
     */
    function parseAction()
    {
        $seq = (int)$this->get('seq', 1);
        $practiseModel = new PractiseModel();
        $uPractise = $practiseModel -> getUserPractiseReport($this->uid, $this->sectionId);
        $practise = $practiseModel -> getPractiseBySeq($this->sectionId, $seq);
        //需要验证是否有错题
        if (!$practise) {
            $this->redirect("/error/?errno=" . Common_Error::ERROR_PRACTISE_NOT_EXISTS);
        }
       
        $tree = $practise['nested_node_tree'];
        $data = array(
            "subject_id" => $practise["item_id"],
            "subject_title" => $tree["node_content"],
            "subject_seq" => $seq,
            "answer" => $practise['answer'],
            "parse" =>$practise['parse'],
            'video_id' => $practise['video_id'],
            "create_time_fmt" => $uPractise["create_time_fmt"],
            "spend_time_fmt" => $uPractise["spend_time_fmt"],
            "answer_list" => $uPractise['answer_list'],
        );

        $practiseNum = $practiseModel -> getSectionPractiseNum($this->sectionId);
        $data["subject_num"] = $practiseNum;
        $data["section_id"] = $this->sectionId;

        foreach ( $tree['nested_node_children'] as $option) {            
            $letter = chr(320+$option["node_index"]);
            $data['option_list'][] = array("id" => $option["node_index"], "letter_seq" => $letter, "content" => $option["node_content"] );
        }

        $statistic = $practiseModel->getPractiseStatistic($this->uid, $practise['item_id'], $this->sectionId);
        $data = array_merge($statistic, $data);
        
        if($this->ajax) {
            $this->displayJson(Common_Error::ERROR_SUCCESS, $data);
        } 
  
        $this->assign("practise", $data);
        $this->assign("section", $this->section);        
        $this->assign("course", $this->course);

        if($this->platform == 'ios' || $this->platform == 'android') {
            $this->getView()->display("practise/parse-app.phtml");
            exit;
        }
    }


    /**
     * 错题解析
     */
    function wrongAction()
    {
        $seq = (int)$this->get('seq', 0);
        $practiseModel = new PractiseModel();        
        $uPractise = $practiseModel -> getUserPractiseReport($this->uid, $this->sectionId);
        $wrongSeqList = $practiseModel -> getUserWrongPractise($this->uid, $this->sectionId);
        //需要验证是否有错题
        if (!$wrongSeqList) {
            $this->redirect("/error/?errno=" . Common_Error::ERROR_PRACTISE_NO_WRONG_PARSE);
        }


        $seq = $seq == 0 ? $wrongSeqList[0] : $seq;
        
        $practise = $practiseModel -> getPractiseBySeq($this->sectionId, $seq);
       
        $tree = $practise['nested_node_tree'];
        $data = array(
            "subject_id" => $practise["item_id"],
            "subject_title" => $tree["node_content"],
            "subject_seq" => $seq,
            "answer" => $practise['answer'],
            "parse" =>$practise['parse'],
            'video_id' => $practise['video_id'],
            "create_time_fmt" => $uPractise["create_time_fmt"],
            "spend_time_fmt" => $uPractise["spend_time_fmt"],
        );
        foreach ($wrongSeqList as $wrong) {
            $data['wrong_practise_seq_list'][] = $wrong['seq'];
        }


        //上一题下一题
        $index = array_search($seq, $wrongSeqList);
        $prev = $index - 1;
        $next = $index + 1;
        if($prev >= 0  && isset($wrongSeqList[$prev])) {
            $data['prev_seq'] = $wrongSeqList[$prev];
        }
        if(isset($wrongSeqList[$next])) {
            $data['next_seq'] = $wrongSeqList[$next];
        }


        $practiseNum = $practiseModel -> getSectionPractiseNum($this->sectionId);
        $data["subject_num"] = $practiseNum;
        $data["section_id"] = $this->sectionId;
        $data["video_url"] = Common_Config::getVideoUrl($this->section['video_id']);

        foreach ( $tree['nested_node_children'] as $option) {            
            $letter = chr(320+$option["node_index"]);
            $data['option_list'][] = array("id" => $option["node_index"], "letter_seq" => $letter, "content" => $option["node_content"] );
        }


        $statistic = $practiseModel->getPractiseStatistic($this->uid, $practise['item_id'], $this->sectionId);
        $data = array_merge($statistic, $data);
        
        if($this->ajax) {
            $this->displayJson(Common_Error::ERROR_SUCCESS, $data);
        } 

        $this->assign("practise", $data);
        $this->assign("section", $this->section);        
        $this->assign("course", $this->course);

        if($this->platform == 'ios' || $this->platform == 'android') {
            $this->getView()->display("practise/wrong-app.phtml");
            exit;
        }
    }

    /**
     * 题干
     */
    function subjectAction()
    {
	
        $practiseId = (int)$this->get("practise_id", 0);
        if (!$practiseId) {
            //
        }

        $practiseModel = new PractiseModel();
        $subject = $practiseModel -> getPractiseSubject($practiseId);
        $this->displayMathML($subject);
    }
    
    /**
     * 
     */
    function optionAction()
    {
        $practiseId = (int)$this->get("practise_id", 0);
        $optionId = (int)$this->get("option_id", 0);
        if (!$practiseId || !$optionId) {
            header("Location:/error/?errno=" . Common_Error::ERROR_PARAM);
            exit;
        }
        
        $practiseModel = new PractiseModel();
        $option = $practiseModel -> getPractiseOption($practiseId, $optionId-1);
        $this->displayMathML($option);
    }


    /**
     * web专用
     */
    function examAction()
    {
        $seq = (int)$this->get('seq', 1);
        $practiseModel = new PractiseModel();
        $practise = $practiseModel -> getPractiseBySeq($this->sectionId, $seq);
        //需要验证是否有错题
        if (!$practise) {
            $this->redirect("/error/?errno=" . Common_Error::ERROR_PRACTISE_NOT_EXISTS);
        }

        $id = $practiseModel -> insertAnswer($this->sectionId, $this->uid, $practise['practise_id'], 0, 0);
       
        $tree = $practise['nested_node_tree'];
        $data = array(
            "subject_id" => $practise["item_id"],
            "subject_seq" => $seq,
            "answer" => $practise['answer'],
            "parse" =>$practise['parse'],
            "video_id" => $practise['video_id'],
            "type_name" => $practise['type_name'],
        );
        $data["subject_title"] = in_array($practise['type_name'], Common_Config::$practiseChoice) 
            ? $practise["nested_content_raw"][0]['content'] : $practiseModel->getSubject($practise["nested_content_raw"]);


        $practiseNum = $practiseModel -> getSectionPractiseNum($this->sectionId);
        $data["subject_num"] = $practiseNum;
        $data["section_id"] = $this->sectionId;

        if (in_array($practise['type_name'], Common_Config::$practiseChoice)) {
            foreach ( $tree['nested_node_children'] as $option) {            
                $letter = chr(320+$option["node_index"]);
                $data['option_list'][] = array("id" => $option["node_index"], "letter_seq" => $letter, "content" => $option["node_content"] );
            }
        }


       
        if($this->ajax) {
            $this->displayJson(Common_Error::ERROR_SUCCESS, $data);
        } 
            
        $this->assign("practise", $data);
        $this->assign("section", $this->section);

        if($this->platform == 'ios' || $this->platform == 'android') {
            $this->getView()->display("practise/exam-app.phtml");
            exit;
        }
    }



}

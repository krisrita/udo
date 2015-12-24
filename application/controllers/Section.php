 <?php
/**
 * 节相关接口
 */
class SectionController extends Base_Section
{

    /**
     * 节的详细页(视频学习页)
     *
     */
    function infoAction()
    {
        $videoId = (int)$this->get("video_id",0);
	    $videoId = $videoId ? $videoId : $this->section['video_id'];
        $videoModel = new VideoModel();
        $video = $videoModel -> getVideo($videoId);
        $video['is_user_like'] = $videoModel->isUserLikeOrDislike($this->uid, $videoId);
        

        $data = array(
            'course_id' => $this->course['id'],
            'course_name' => $this->course['name'],
            'chapter_id' => $this->chapter['id'],
            'chapter_name' => $this->chapter['name'],
            'chatper_seq' => $this->chapter['seq'],
            'section_id' => $this->section['id'],
            'section_name' => $this->section['name'],
            'section_seq' => $this->section['seq'],
            'video_id' => $video['id'],
            'video_url' => $video['url'],
            'video_duration' => $video['duration'],
            'duration' => $video['duration'],
            'video_image' => $video['image'],
            'video_like_num' => $video['like_num'],
            'video_dislike_num' => $video['dislike_num'],
            'video_view_num' => $video['view_num'],
            'is_user_like' => $video['is_user_like'],
        );

        if (!$this->teacher || $this->teacher['id'] != $video['user_id']) {
            $userModel = new UserModel();
            $this->teacher = $userModel->getTeacher($video['user_id']);            
        }

        $data['teacher'] = array(
            'id' => $this->teacher['id'],
            'name' => $this->teacher['name'], 
            'job_title' => $this->teacher['job_title'],
            'avator' => $this->teacher['avator'],
        );


        
        if ($this->isMobile) {
            $this->displayJson(Common_Error::ERROR_SUCCESS, $data);
        }


        if ($this->course['type'] == 1) {
            $practiseModel = new PractiseModel();
            $practise = $practiseModel->getVideoBelongPractise($videoId, $this->course['id']);
            $this->assign("practise", $practise);
        }

        $this->assign("course", $this->course);
        $this->assign("chapter", $this->chapter);
        $this->assign("section", $this->section);
        $this->assign("teacher", $this->teacher);
        $this->assign("video", $video);
    }


    /**
     * 笔记列表
     */
    function noteAction()
    {    
        $noteModel = new NoteModel();
        $noteList = $noteModel -> getSectionNote($this->sectionId);
        if ($this->isMobile) {
            $this->displayJson(Common_Error::ERROR_SUCCESS, $noteList);
        }
        $this->assign('section', $this->section);
        $this->assign('chapter', $this->chapter);
        $this->assign('course', $this->course);
        $this->assign('teacher', $this->teacher);
        $this->assign("note_list", $noteList);
     }

    /**
     * 练习题
     */
    function practiseAction()
    {
        $practiseModel = new PractiseModel();
        $rs = $practiseModel->isUserPractiseFinished($this->uid, $this->sectionId);
        //不能重复答题
        if($rs) {
            header("Location:/practise/report/section_id/" . $this->sectionId);
            exit;
        }


        $seq = (int)$this->get('seq', 1);
        
        $practise = $practiseModel -> getPractiseBySeq($this->sectionId, $seq);
        $tree = $practise['nested_node_tree'];
        $subjectUrl = sprintf("%s/practise/subject/section_id/%d/practise_id/%d", Common_Config::BASE_URL, $this->sectionId, $practise['item_id']);

        $data = array(
            "subject_id" => $practise["item_id"],
            "subject_title" => $tree["node_content"],
            "subject_seq" => $seq,
            "url" => $subjectUrl,
        );
        $practiseNum = $practiseModel -> getSectionPractiseNum($this->sectionId);
        $data["subject_num"] = $practiseNum;
        $data["section_id"] = $this->sectionId;

        
        //上一题下一题
        $prev = $seq - 1;
        $next = $seq + 1;
        if($prev >= 1) {
            $data['prev_seq'] = $prev;
        }
        if($next <= $data['subject_num']) {
            $data['next_seq'] = $next;
        }

        foreach ( $tree['nested_node_children'] as $option) {
            $letter = chr(320+$option["node_index"]);
            $optionUrl = sprintf("%s/practise/option/section_id/%d/practise_id/%d/option_id/%d", Common_Config::BASE_URL, $this->sectionId, $practise['item_id'], $option["node_index"]);
            $data['option_list'][] = array(
                "id" => $option["node_index"], 
                "letter_seq" => $letter, 
                "content" => $option["node_content"],
                "url" => $optionUrl
                );
        }
        
        if($this->isMobile || $this->ajax ) {
            if($this->platform == 'ios') {
                $data['subject_title'] = '';
                foreach ($data['option_list'] as &$opt) {
                    $opt['content'] = '';
                }
            }
            $this->displayJson(Common_Error::ERROR_SUCCESS, $data);
        }



        $this->section['practise_num'] = $practiseModel->getSectionPractiseNum($this->sectionId);
        $this->assign('section', $this->section);
        $this->assign('chapter', $this->chapter);
        $this->assign('course', $this->course);

    }

    /**
     * 回答
     */
    function answerAction()
    {        
        $practiseModel = new PractiseModel();
        $rs = $practiseModel->isUserPractiseFinished($this->uid, $this->sectionId);        
        //不能重复答题
        if($rs) {
            $this->displayJson(Common_Error::ERROR_PRACTISE_HAS_BEEN_DONE);
        }

        //回答的时候一定要按题目的排序插入．！！！！
        $answerList = $this->post("answer", 0);
        $spendTime = (int)$this->post("spend_time" , 0);
        if (!$answerList || !$spendTime) {
            $this->displayJson(Common_Error::ERROR_PARAM);
        }

               

        $answerList = explode(",", $answerList);
        $result = array();
        foreach ($answerList as $answer) {
            list($practiseId, $optionId) = explode("_", $answer);
            $result[$practiseId] = $optionId;
        }        
        
        
        $practiseModel = new PractiseModel();
        $practiseList = $practiseModel->getSectionPractiseList($this->sectionId);    
        
        $full = array();
        foreach ($practiseList as $practise) {
            $full[$practise['practise_id']] = isset($result[$practise['practise_id']]) ? $result[$practise['practise_id']] : 0;
        }

        $correctNum = 0;
        foreach ($full as $practiseId=>$optionId) 
        {
            //正确答案
            $answerId = $practiseModel -> getPractiseAnswerId($practiseId);
            $id = $practiseModel -> insertAnswer($this->sectionId, $this->uid, (int)$practiseId, (int)$optionId, $answerId);
            if ($optionId != 0 && $practiseModel -> check($practiseId, $optionId)) {
                $correctNum++;
            }
        }

        $practiseModel -> insertUserPractise($this->uid, $this->course['id'], $this->sectionId, $spendTime, $correctNum);


        $this->displayJson(Common_Error::ERROR_SUCCESS);
    }

}


<?php

class VideoController extends Base_Contr
{

    /**
     * 视频顶
     */
    function likeAction()
    {
        $like = 1;
        $videoId = (int)$this->get("video_id", 0); 
        if (!$videoId) {
            $this->displayJson(Common_Error::ERROR_PARAM);
        }

        $videoModel = new VideoModel();
        $section = $videoModel -> getVideoBelongSection($videoId);
        $courseId = 0;
        if (!$section) {
            $practise = $videoModel->getVideoBelongPractise($videoId);
            $courseId = $practise ? $practise['course_id'] : $courseId;
        } else {
            $courseId = $section['course_id'];
        }

        $rs = $videoModel -> like($this->uid, $videoId, $like, $courseId);
        $this->displayJson(Common_Error::ERROR_SUCCESS, $rs);
    }

    /**
     * 视频踩
     */
    function dislikeAction()
    {
        $like = 2;
        $videoId = (int)$this->get("video_id", 0); 
        if (!$videoId) {
            $this->displayJson(Common_Error::ERROR_PARAM);
        }

        $videoModel = new VideoModel();
        $section = $videoModel -> getVideoBelongSection($videoId);
        $courseId = 0;
        if (!$section) {
            $practise = $videoModel->getVideoBelongPractise($videoId);
            $courseId = $practise ? $practise['course_id'] : $courseId;
        } else {
            $courseId = $section['course_id'];
        }
        
        $rs = $videoModel -> like($this->uid, $videoId, $like, $courseId);
        $this->displayJson(Common_Error::ERROR_SUCCESS, $rs);
    }


    //http://182.92.110.119/video/play/video_id/1?token=d7cff025b89de9f77b1b6234fea39f99
    function playAction()
    {   
        //视频观看次数++
        $videoId = (int)$this->get("video_id", 0);
        $videoModel = new VideoModel();
        $videoModel->incrViewNum($videoId);

        $videoModel -> insert($this->uid, $videoId);



        //用户人数        
        $section = $videoModel -> getVideoBelongSection($videoId);  
        if ($section) {
            $courseModel = new CourseModel();
            $courseModel->incrViewNum($section['course_id']);
        }

        $this->displayJson(Common_Error::ERROR_SUCCESS);
    }



}
<?php

/**
 * 试卷模板
 */
class PaperModel
{
    function getList($courseId, $paperId, $type, $pagesize)
    {
        $tblSection = new DB_Haodu_CourseSection();
        $where = "where course_id={$courseId}";
        $orderby = "order by id desc";

        if ($paperId > 0) {
            if($type == 1) {
                $where .= " and id < $paperId";
                $orderby = "order by id desc";
            } else {            
                $where .= " and id > $paperId";
                $orderby = "order by id asc";
            }
        } 

        return $tblSection->fetchLimit("*", $where, $orderby, 1, $pagesize);
    }

    function getAll($courseId)
    {
        $tblSection = new DB_Haodu_CourseSection();
        $where = "where course_id={$courseId}";
        $orderby = "order by seq asc";
        return $tblSection->fetchAll("*", $where, $orderby);
    }


    function getPaperDuration($courseId)
    {        
        $tblCoursePractise = new DB_Haodu_CoursePractise();
        $videoList = $tblCoursePractise->fetchAll("video_id", "where course_id={$courseId}");
        if(!$videoList) return 0;

        $videoIds = $tblCoursePractise->columnRow($videoList, "video_id");
        $tblVideo = new DB_Haodu_CourseVideo();
        $video = $tblVideo->scalar("sum(duration) as total_duration", "where id in (".implode(',', $videoIds).")");
        return (int)$video['total_duration'];
    }

    function getUserPaperDuration($uid, $courseId)
    {   
        $tblUserSection = new DB_Haodu_UserSection();
        $list = $tblUserSection -> fetchAll("section_id", "where uid={$uid} and course_id={$courseId}");
        if(!$list) return 0;
        
        $sectionIds = $tblUserSection->columnRow($list, "section_id");
        $tblPractiseAnswerHistory = new DB_Haodu_PractiseAnswerHistory();
        $practiseList = $tblPractiseAnswerHistory -> fetchAll("practise_id", "where uid={$uid} and section_id in (".implode(",", $sectionIds).")");
        if(!$practiseList) return 0;

        $practiseIds = $tblPractiseAnswerHistory->columnRow($practiseList, "practise_id");
        $tblCoursePractise = new DB_Haodu_CoursePractise();
        $videoList = $tblCoursePractise->fetchAll("video_id", "where practise_id in (".implode(",", $practiseIds).")");
        if(!$videoList) return 0;
        
        $videoIds = $tblCoursePractise->columnRow($videoList, "video_id");
        $tblUserVideo = new DB_Haodu_UserVideo();
        $videoList = $tblUserVideo -> fetchAll("video_id", "where uid={$uid} and video_id in (".implode(",", $videoIds).")");
        if (!$videoList) return 0;

        $videoIds = $tblCoursePractise->columnRow($videoList, "video_id");       


        $tblVideo = new DB_Haodu_CourseVideo();
        $video = $tblVideo->scalar("sum(duration) as total_duration", "where id in (".implode(',', $videoIds).")");
        return (int)$video['total_duration'];
    }




}
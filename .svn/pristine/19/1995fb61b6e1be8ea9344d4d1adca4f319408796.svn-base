<?php

class CommentModel
{


    /**
     * 评论列表
     */
    public function getCommentList($commentId, $courseId, $videoId = 0, $type = 0, $pagesize = 0)
    {
        $tbl = new DB_Haodu_CourseComment();
        
        $orderby = "order by id desc";
        $where = "where course_id = {$courseId}";

        if ($videoId > 0) {
            $where .= " and video_id = {$videoId}";
        }

        if ($commentId > 0) {
            //取新
            if ($type == 0) {
                $where .= " and id > {$commentId}";
                //$orderby = "order by id asc";
            } else {                
                $where .= " and id < {$commentId}";
                $orderby = "order by id desc";                
            }
        }

        $list = $tbl -> fetchLimit("*", $where, $orderby, 1, $pagesize);
        $list = $tbl->kv($list, "id");

        return $list;
    }
    

    /**
     * 获得评论
     */
    public function getComment($id)
    {
        $tbl = new DB_Haodu_CourseComment();
        return $tbl->fetchRow($id);
    }

    /**
     * 插入评论
     * curl -d "course_id=1&video_id=1&content=abc&debug" http://182.92.110.119/comment/publish
     */
    public function insertComment($data)
    {
        $data['create_time'] = time();
        $tbl = new DB_Haodu_CourseComment();
        return $tbl->insert($data);
    }


    /**
     * 举报次数+1
     */ 
    public function incrReportNum($commentId)
    {
        $tbl = new DB_Haodu_CourseComment();
        $tbl->increase($commentId, array("report_num" => 1));
    }
}
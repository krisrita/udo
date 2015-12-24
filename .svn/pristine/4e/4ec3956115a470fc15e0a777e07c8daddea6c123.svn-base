<?php


class VideoModel
{

    /**
     * 视频信息
     */
    public function getVideo($videoId)
    {
        $tblVideo = new DB_Haodu_CourseVideo();
        $video = $tblVideo->fetchRow($videoId);
        if ($video) {
            $video['url'] = Common_Config::getVideoUrl($videoId);
            $video['image'] = Common_Config::getVideoImageUrl($video['image']);
            $video['duration_fmt'] = sprintf("%'.02d", floor($video['duration'] / 60)) . ":" . sprintf("%'.02d", $video['duration'] % 60);
            $video['comment_num'] = $this->getVideoCommentNum($video['id']);
            return $video;
        }
        
        return false;
    }

    /**
     * 视频评论数量
     */
    public function getVideoCommentNum($videoId)
    {
        $tbl = new DB_Haodu_CourseComment();
        return $tbl -> queryCount("where video_id = {$videoId}");
    }


    /**
     * 视频被学习次数
     */
    public function incrViewNum($videoId)
    {
        $tblVideo = new DB_Haodu_CourseVideo();
        $tblVideo->increase($videoId, array("view_num"=>1));
    }

    public function incrLikeNum($videoId, $num = 1)
    {
        $tblVideo = new DB_Haodu_CourseVideo();
        $tblVideo->increase($videoId, array("like_num"=>$num));
    }

    public function incrDislikeNum($videoId, $num = 1)
    {
        $tblVideo = new DB_Haodu_CourseVideo();
        $tblVideo->increase($videoId, array("dislike_num"=>$num));
    }


    /**
     * 用户是否踩过或顶过
     */
    public function isUserLikeOrDislike($uid, $videoId)
    {        
        $tbl = new DB_Haodu_UserVideo();
        $record = $tbl -> scalar("*", "where uid={$uid} and video_id={$videoId}");
        if ($record) {
            return $record['like_type'];
        }
        return 0;
    }

    /**
     * 用户看视频日志(最后一次)
     */
    public function insert($uid, $videoId)
    {
        $time = time();
        $data = array("uid" => $uid, "video_id" => $videoId, "create_time" => $time);
        $tbl = new DB_Haodu_UserVideo();
        return $tbl->insert($data); 
    }


    /**
     * 用户对视频的评价(顶，踩)
     */
    public function like($uid, $videoId, $like = 1, $courseId = 0)
    {
        $flag = false;
        $courseModel = new CourseModel();
        $tbl = new DB_Haodu_UserVideo();
        $record = $tbl -> scalar("*", "where uid={$uid} and video_id={$videoId}");
        
        //只一次
        if ($record) {
            return true;
        }

        $time = time();
        $data = array("uid" => $uid, "video_id" => $videoId, "like_type" => $like, "create_time" => $time);
        $flag = $tbl->insert($data); 
        
        if($flag) {
             if ($like == 1) {
                $this->incrLikeNum($videoId, array('like_num'=>1));                    
                $courseModel->incrLikeNum($courseId, array('like_num'=>1));
            } else {
                $this->incrDislikeNum($videoId, array('dislike_num'=>1));                    
                $courseModel->incrDislikeNum($courseId, array('dislike_num'=>1));
            }       
        }
    }


    /**
     * 用户视频投票
     */
    public function getUserVideoVote($uid, $videoId)
    {
        $tbl = new DB_Haodu_UserVideo();
        return $tbl -> scalar("*", "where uid={$uid} and video_id={$videoId}");
    }
    
    /**
     * 视频所属节
     */
    public function getVideoBelongSection($videoId)
    {
        $tbl = new DB_Haodu_CourseSection();
        return $tbl->scalar("*", "where video_id={$videoId}");
    }

    /**
     * 视频所属习题
     */
    public function getVideoBelongPractise($videoId)
    {
        $tbl = new DB_Haodu_CoursePractise();
        return $tbl->scalar("*", "where video_id={$videoId}");
    }



}

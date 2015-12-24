<?php

class CourseModel
{


    /**
     * 用户可以看到的学校
     */
    function getUserCourseIds($uid)
    {
        $tbl = new DB_Haodu_CourseUser();
        $list = $tbl->fetchAll("course_id", "where uid={$uid}");

        if(!$list) return false;

        return $tbl->columnRow($list, "course_id");
    }

    
    /**
     * 课程列表
     */
    function getList($schoolId, $page, $pagesize, $uid)
    {
        $where = "where school_id={$schoolId}";
        $courseIds = $this->getUserCourseIds($uid);
        if ($courseIds) {
            $where .= "  and (open_mode=0 or id in (".implode(",", $courseIds)."))";
        } else {
            $where .= " and open_mode=0";
        }

        $tblCourse = new DB_Haodu_Course();        
        $count = $tblCourse->queryCount($where);

        if ($count && $count > 0) {
            $list = $tblCourse->fetchLimit("id,name,type,image,like_num,dislike_num,view_num,duration", $where, "order by id desc", $page, $pagesize);
            foreach ($list as &$course) {
                $course['image'] = Common_Config::getCourseImageUrl($course['image']);
                $course['duration_fmt'] = sprintf("%'.02d", floor($course['duration'] / 60)) . ":" . sprintf("%'.02d", $course['duration'] % 60);
            }
            unset($course);
        } else {
            $list = NULL;
        }

        $data['list'] = $list;
        $data['count'] = $count;

        return $data;
    }




    /**
     *　移动端获取数据
     */
    function getCourseListById($schoolId, $courseId, $type, $pagesize, $uid)
    {
        
        $where = "where school_id={$schoolId}";

        $courseIds = $this->getUserCourseIds($uid);
        if ($courseIds) {
            $where .= " and (open_mode=0 or id in (".implode(",", $courseIds)."))";
        } else {
            $where .= " and open_mode=0";
        }
        $orderby = "order by id desc";

        if ($courseId > 0) {
            if($type == 1) {
                $where .= " and id < $courseId";
                $orderby = "order by id desc";
            } else {            
                $where .= " and id > $courseId";
                //$orderby = "order by id asc";
            }
        } 


        $tblCourse = new DB_Haodu_Course();      
        $list = $tblCourse->fetchLimit("id,name,type,image,like_num,dislike_num,view_num,comment_num,chapter_num,section_num,duration", $where, $orderby, 1, $pagesize);
        foreach ($list as &$course) {
            $course['image'] = Common_Config::getCourseImageUrl($course['image']);
            $course['duration'] = $this->getCourseDuration($course['id']);
            $course['duration_fmt'] = sprintf("%'.02d", floor($course['duration'] / 60)) . ":" . sprintf("%'.02d", $course['duration'] % 60);
            $course['chapter_num'] = $this->getCourseChapterTotal($course['id']);
            $course['section_num'] = $this->getCourseSectionTotal($course['id']);
            $course['comment_num'] = $this->getCourseCommentTotal($course['id']);

        }
        unset($course);
        return $list;
    }
    
    /**
     * 获得一门课的数据
     */
    public function getCourse($cid)
    {
        $tblCourse = new DB_Haodu_Course();
        $course = $tblCourse->fetchRow($cid);   
        if ($course) {
            $course['image'] = Common_Config::getCourseImageUrl($course['image']);
            $course['duration'] = $course['duration'] > 0 ? $course['duration'] : $this->getCourseDuration($cid);
        }
        
        return $course;
    }

    /**
     * 临时::课程的学习时长
     * 数据导入时没有对一些统计数据加处理.所以这里做了一个重新统计.
     */
    public function getCourseDuration($courseId)
    {
        $tblSection = new DB_Haodu_CourseSection();
        $list = $tblSection->fetchAll("video_id", "where course_id = {$courseId} and parent_id > 0");        
        if (!$list) return 0;

        $videoIds = array();
        foreach ($list as $section) {
            $videoIds[] = $section['video_id'];
        }

        
        $tblVideo = new DB_Haodu_CourseVideo();
        $sum = $tblVideo->scalar("sum(duration) as course_duration", "where id in (".implode(',', $videoIds).")");
        return $sum['course_duration'];
    }

    /**
     * 临时::课程的章数
     * 数据导入时没有对一些统计数据加处理.所以这里做了一个重新统计.
     */
    public function getCourseChapterTotal($courseId)
    {
        $tblSection = new DB_Haodu_CourseSection();
        return $tblSection->queryCount("where course_id = {$courseId} and parent_id = 0");
    }


    /**
     * 临时::课程的节数
     * 数据导入时没有对一些统计数据加处理.所以这里做了一个重新统计.
     */
    public function getCourseSectionTotal($courseId)
    {
        $tblSection = new DB_Haodu_CourseSection();
        return $tblSection->queryCount("where course_id = {$courseId} and parent_id > 0");
    }


    /**
     * 临时::课程的评论数
     * 数据导入时没有对一些统计数据加处理.所以这里做了一个重新统计.
     */
    public function getCourseCommentTotal($courseId)
    {
        $tblComment = new DB_Haodu_CourseComment();
        return $tblComment->queryCount("where course_id = {$courseId}");
    }

    
    /**
     * 节信息
     */
    function getSection($sectionId)
    {
        $tblSection = new DB_Haodu_CourseSection();
        return $tblSection -> fetchRow($sectionId);
    }


    /**
     * 章列表
     */
    public function getChapterList($cid)
    {
        $tblSection = new DB_Haodu_CourseSection();
        $list = $tblSection->fetchAll("id, course_id, name, seq, open_time", "where course_id = {$cid} and parent_id=0", "order by seq asc");
        foreach ($list as &$chapter) {
            $chapter['open_time_remain'] = $chapter['open_time'] > time() ? $chapter['open_time'] - time() : 0;
            $chapter['open_time_fmt'] = Common_Time::remain($chapter['open_time']);
        }
        unset($chapter);
        return $list;
    }

    /**
     * 章节列表array('chapter_id'=> array(array(节信息))
     */
    public function getSectionList($cid)
    {
        $tblSection = new DB_Haodu_CourseSection();
        $list = $tblSection->fetchAll("id, video_id, course_id, parent_id as chapter_id, name, seq", "where course_id = {$cid} and parent_id > 0", "order by seq asc");

        $tblVideo = new DB_Haodu_CourseVideo();
        foreach ($list as &$section) {            
            $video = $tblVideo->fetchRow($section['video_id'], "duration"); 
            $section['duration'] = $video['duration'];
            $section['duration_fmt'] = sprintf("%'.02d", floor($video['duration'] / 60)) . ":" . sprintf("%'.02d", $video['duration'] % 60);
        }
        $list = $tblSection->map($list, "chapter_id");
        return $list;
    }



    /**
     * 课程获得老师信息
     */
    public function getCourseTeachers($courseId)
    {
        $teachers = array();
        $tblCourseTeacher = new DB_Haodu_CourseTeacher();
        $list = $tblCourseTeacher->fetchAll("teacher_id", "where course_id = $courseId", "order by id asc");

        if ($list) {
            $tblUser = new DB_Howdo_User();
            foreach ($list as $row) {
                $teacher = $tblUser->fetchRow($row['teacher_id'], "stuff_id as id, avator, stuff_name as name, gender, job_title, info");
                if ($teacher) {
                    $teacher['avator'] = empty($teacher['avator']) ? Common_Config::DEFAULT_AVATOR : $teacher['avator'];
                    $teacher['avator'] = Common_Config::AVATOR_BASE_URL . $teacher['avator'];
                }
                $teachers[] = $teacher;
            }
        }
        return $teachers;
        
    }

    /**
     * 课程下用户节的学习数据
     */
    public function getUserCourseSection($uid, $courseId) 
    {
        $tblUserSection = new DB_Haodu_UserSection();
        $list = $tblUserSection -> fetchAll("course_id, section_id, last_time", "where uid={$uid} and course_id={$courseId}", "order by last_time desc");
        if ($list) {
            return $tblUserSection->kv($list, "section_id");
        }
        return false;
    }
   

    /**
     * 用户最后学习的节
     * @param $uid
     * @param array $courseIds
     */
    function getUserCourseLastSection($uid, $courseIds = array())
    {
        $course = array();
        $inCourseIds = implode(",", $courseIds);
        $tblUserSec = new DB_Haodu_UserSection();

        $last = array();
        foreach ($courseIds as $cid) {
            $rs = $tblUserSec->scalar("course_id, section_id", "where uid={$uid} and course_id={$cid}", "order by last_time desc");
            if ($rs) {
                $last[] = $rs;  
            }
        }

        if ($last) {
            $tblCourSec = new DB_Haodu_CourseSection();
            foreach ($last as $lastSec) {
                $section = $tblCourSec -> fetchRow($lastSec['section_id'], 'id,parent_id,name,seq');
                $chapter = $tblCourSec -> fetchRow($section['parent_id'],"id,name,seq");
                $course[$lastSec['course_id']] = array(
                    'last_section_id' => $section['id'],
                    'last_section_name' => $section['name'],
                    'last_section_seq' => $section['seq'],
                    'last_chapter_id' => $chapter['id'],
                    'last_chapter_name' => $chapter['name'],
                    'last_chapter_seq' => $chapter['seq'],
                );
            }
        }

        return $course;
    }


    /**
     * 课程下用户答题数量
     */
    public function getUserCoursePractiseNum($uid, $courseId)
    {
        $sectionIds = $this->getUserCourseSection($uid, $courseId);
        if (!$sectionIds) return 0;
        $sectionIds = array_keys($sectionIds);

        $tblUserPractise = new DB_Haodu_PractiseAnswerHistory();
        return $tblUserPractise -> queryCount("where uid=$uid and section_id in (".implode(",",$sectionIds).")");
    }

    /**
     * 课程下练习题提交数据　
     */
    public function hasCoursePractiseFinished($uid, $courseId = array())
    {
        $tbl = new DB_Haodu_UserPractise();
        $list = $tbl->fetchAll("section_id,create_time", "where uid={$uid} and course_id={$courseId}");
        return $tbl->map($list, "section_id");
    }

    /**
     * 用户节下练习数据
     */
    public function getUserSectionPractise($uid, $sectionId)
    {
        $tbl = new DB_Haodu_UserPractise();
        $all = $tbl -> fetchAll("*", "where uid={$uid} and section_id={$sectionId}");
        return $all;
    }



    /**
     * 用户与章节
     */
    public function updateUserSection($uid, $sectionId, $courseId)
    {
        $tblUserSection = new DB_Haodu_UserSection();
        $rs = $tblUserSection -> scalar("id", "where uid={$uid} and section_id={$sectionId}", "");
        if ($rs) {
            $lastTime = time();
            $tblUserSection -> update($rs['id'], array('last_time' => $lastTime));
        } else {
            $data = array("uid" => $uid, "section_id" => $sectionId, "course_id" => $courseId);
            $tblUserSection -> insert($data);
        }
    }
    

    public function incrViewNum($courseId)
    {
        $tblVideo = new DB_Haodu_Course();
		$tblVideo->increase($courseId, array("view_num"=>1));
    }

    public function incrLikeNum($courseId, $num = 1)
    {
        $tblVideo = new DB_Haodu_Course();
        $tblVideo->increase($courseId, array("like_num"=>$num));
    }

    public function incrDislikeNum($courseId, $num = 1)
    {
        $tblVideo = new DB_Haodu_Course();
        $tblVideo->increase($courseId, array("dislike_num"=>$num));
    }

    function getCoursePractiseList($courseId)
    {
        $tblCoursePractise = new DB_Haodu_CoursePractise();
        $list = $tblCoursePractise->fetchAll("section_id, seq, video_id", "where course_id={$courseId}", "order by seq asc, id asc");
        if (!$list) return array();

        $data = array();
        foreach($list as $row) {
            $data[$row['section_id']][$row['seq']] = $row['video_id'];
        }
        return $data;
    }

    /**
     * 课程总的试题数
     */
    function getCoursePractiseNum($courseId)
    {
        $tblCoursePractise = new DB_Haodu_CoursePractise();
        return $tblCoursePractise->queryCount("where course_id={$courseId}");
    }
}

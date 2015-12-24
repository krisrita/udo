 <?php
/**
 * 评论关接口
 */
class CommentController extends Base_Contr
{
    /**
     * 课程评论
     */
    function listAction()
    {
        $pagesize = 10;
        $courseId = (int)$this->get("course_id", 0);
        $videoId = (int)$this->get("video_id", 0);
        $commentId = (int)$this->get("comment_id", 0);
        $type = (int)$this->get("type", 0);

        $commentModel = new CommentModel();
        $data = $commentModel -> getCommentList($commentId, $courseId, $videoId, $type, $pagesize);

        if (!empty($data)) {
            $userModel = new UserModel();
            $videoModel = new VideoModel();
            $courseModel = new CourseModel();
            foreach ($data as &$comment) {
                $author = $userModel->getUser($comment['uid']);
                $comment['create_time_fmt'] = Common_Time::flow($comment['create_time']);
                $comment['author_uid'] = $author['stuff_id'];
                $comment['author_name'] = $author['stuff_name'];
                $comment['author_avator'] = $author['avator'];

                if ($comment['comment_id']) {
                    $parentComment = $commentModel -> getComment($comment['comment_id']);                    
                    $beReplyAuthor = $userModel->getUser($parentComment['uid']);
                    $parentComment['author_uid'] = $beReplyAuthor['stuff_id'];
                    $parentComment['author_name'] = $beReplyAuthor['stuff_name'];
                    $parentComment['author_avator'] = $beReplyAuthor['avator'];
                    $comment['parent_comment'] = $parentComment;
                }

                if ($comment['video_id']) {
                    $section = $videoModel -> getVideoBelongSection($comment['video_id']);
                    if (!$section) {
                        $practiseModel = new PractiseModel();
                        $practise = $practiseModel->getVideoBelongPractise($comment['video_id'], $courseId);
                        $comment['practise_seq'] = $practise['seq'];
                        $section = $courseModel->getSection($practise['section_id']);
                    }

                    $chapter = $courseModel -> getSection($section["parent_id"]);
                    $comment["chapter_id"] = $chapter["id"];                        
                    $comment["chapter_name"] = $chapter["name"];
                    $comment["chapter_seq"] = $chapter["seq"];
                    $comment["section_id"] = $section["id"];                        
                    $comment["section_name"] = $section["name"];
                    $comment["section_seq"] = $section["seq"];
                    $userVideo = $videoModel->getUserVideoVote($comment['uid'], $comment['video_id']);
                    $comment["video_like"] = isset($userVideo['like_type']) ? $userVideo['like_type'] : 0;
                }
            } 
            unset($comment);
        }       

        


        $this->displayJson(Common_Error::ERROR_SUCCESS, array_values($data));
    }


    /**
     * 发表评论
     * curl -d "course_id=1&comment_id=1&video_id=0&content=helloaaaaaaaaaa" http://182.92.110.119/comment/publish
     */
    function publishAction()
    {
        $courseId = (int)$this->post("course_id", 0);
        $commentId = (int)$this->post("comment_id", 0);
        $videoId = (int)$this->post("video_id", 0);
        $content = $this->post("content", "");

        $time = time();        
        $comment = array(
            "uid" => $this->uid, 
            "comment_id" => $commentId, 
            "course_id" => $courseId, 
            "video_id" => $videoId, 
            "content" => $content,
            "create_time" => $time,
        );

        $commentModel = new CommentModel();
        $id = $commentModel -> insertComment($comment);

        if ($id) {            
            $userModel = new UserModel();
            $videoModel = new VideoModel();
            $courseModel = new CourseModel();

            $author = $userModel->getUser($comment['uid']);
            $comment['create_time_fmt'] = Common_Time::flow($comment['create_time']);
            $comment['author_uid'] = $author['stuff_id'];
            $comment['author_name'] = $author['stuff_name'];
            $comment['author_avator'] = $author['avator'];
            if ($comment['comment_id']) {
                $parentComment = $commentModel -> getComment($comment['comment_id']);                    
                $beReplyAuthor = $userModel->getUser($parentComment['uid']);
                $parentComment['author_uid'] = $beReplyAuthor['stuff_id'];
                $parentComment['author_name'] = $beReplyAuthor['stuff_name'];
                $parentComment['author_avator'] = $beReplyAuthor['avator']; 
                $parentComment['create_time_fmt'] = Common_Time::flow($parentComment['create_time']);
                $comment['parent_comment'] = $parentComment;

            }
            if ($comment['video_id']) {
                $section = $videoModel -> getVideoBelongSection($comment['video_id']);
                if (!$section) {
                    $practiseModel = new PractiseModel();
                    $practise = $practiseModel->getVideoBelongPractise($comment['video_id'], $courseId);
                    $comment['practise_seq'] = $practise['seq'];
                    $section = $courseModel->getSection($practise['section_id']);
                }

                $chapter = $courseModel -> getSection($section["parent_id"]);
                $comment["chapter_id"] = $chapter["id"];                        
                $comment["chapter_name"] = $chapter["name"];
                $comment["chapter_seq"] = $chapter["seq"];
                $comment["section_id"] = $section["id"];                        
                $comment["section_name"] = $section["name"];
                $comment["section_seq"] = $section["seq"];
                $userVideo = $videoModel->getUserVideoVote($comment['uid'], $comment['video_id']);
                $comment["video_like"] = isset($userVideo['like_type']) ? $userVideo['like_type'] : 0;
            }
            $this->displayJson(Common_Error::ERROR_SUCCESS, array($comment));
        }

        $this->displayJson(Common_Error::ERROR_MYSQL_EXECUTE);
    }


    /**
     * 举报
     * curl -d "comment_id=1" http://182.92.110.119/comment/report
     */
    function reportAction()
    {
        $commentId = $this->post("comment_id", 0);
        $commentModel = new CommentModel();
        $rs = $commentModel -> incrReportNum($commentId);
        if (false !== $rs) {
            $this->displayJson(Common_Error::ERROR_SUCCESS);
        }
        $this->displayJson(Common_Error::ERROR_MYSQL_EXECUTE);
    }
}
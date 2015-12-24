 <?php
/**
 * 反馈，意见
 */
class FeedbackController extends Base_Contr
{    
    public $needLogin = false;

    /**
     * 提交反馈
     * curl -d "content=内容&contact=1300000000" http://182.92.110.119/feedback/publish
     */
    public function publishAction()
    {
        $content = $this->post("content", "");
        $contact = $this->post("contact", "");
        $time = time();
        $data = array("uid" => $this->uid, "content" => $content, "contact" => $contact, "create_time" => $time);
        $tbl = new DB_Haodu_Feedback();
        $feedbackId = $tbl->insert($data);

        if ($feedbackId) {
            $this->displayJson(Common_Error::ERROR_SUCCESS, array("feedback_id" => $feedbackId));
        }

        $this->displayJson(Common_Error::ERROR_MYSQL_EXECUTE);
    }

    public function indexAction()
    {
    }
}
<?php
class ApiController extends Base_Contr 
{

	/**
	 * 调用入口
	 */
	public function call()
	{
		$act  = $_GET['act'];
		$token = $_GET['token'];
	}



	/**
	 * 用户::登录
	 */
	private function userLoginAction()
	{
	}

	/**
	 * 用户::注册
	 */
	private function userRegAction()
	{
	}

	/**
	 * 用户::修改信息
	 */
	private function setUserInfoAction()
	{
	}	
	

	/**
	 * 课程::列表
	 */
	private function courseListAction() 
	{		
	}

	/**
	 * 课程::章节列表
	 */
	private function chapterListAction()
	{
	}

	/**
	 * 课程::详细
	 */
	private function courseInfoAction()
	{
	}

	/**
	 * 视频::顶/踩
	 */
	private function videoLikeAction()
	{
	}


	/**
	 * 练习题::列表
	 */
	private function practiseListAction()
	{
	}


	/**
	 * 练习题::解析列表
	 */
	private function practiseParseListAction()
	{
	}


	/**
	 * 练习题::用户提交答案
	 */
	private function submitAnswerAction()
	{
	}


	/**
	 * 评论::列表
	 */
	private function bbsListAction()
	{
	}


	/**
	 * 评论::发表
	 */
	private function addBbsAction()
	{
	}

	/**
	 * 举报
	 */
	private function reportAction()
	{
	}

	/**
	 * 反馈
	 */
	private function feedbackAction()
	{
	}

    /**
     * 获取个性化频道列表
     */
    private function personalListAction()
    {

    }

}
?>
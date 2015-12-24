<?php
session_start();
/**
 * Class IndexController
 */
class IndexController extends Base_Contr 
{
    /**
     * 首页第一期
     */
    /*
	public function indexAction() 
	{
        $pagesize = 9;
        $request = $this->getRequest();
        $page = $request -> getParam('page', 1);

        $courseModel = new CourseModel();
        $data = $courseModel -> getList($page, $pagesize);

        if($this->uid && $data['list']) {
            $courseIds = array();
            foreach ($data['list'] as $course) {
                $courseIds[] = $course['id'];
            }
            $lastLearn = $courseModel -> getUserCourseLastSection($this->uid, $courseIds);
            foreach ($data['list'] as &$course) {
                $course = isset($lastLearn[$course['id']]) ? array_merge($course, $lastLearn[$course['id']]) : $course;
            }
            unset($course);
        }

        $this->assign('course_list', $data['list']);
        $this->assign('course_count', $data['count']);
        $this->assign('page_count', ceil($data['count'] / $pagesize));
	}
    */

    /**
     *　第二期首页
     */
    public function indexAction()
    {
        $cityId = (int)$this->get("city_id", 0);

        $datacourseModel = new DatasourceModel();
        $city = $datacourseModel->getCity($cityId);

        $schoolModel = new SchoolModel();
        $list = $schoolModel -> getSchoolList($cityId, $this->uid);
        $this->assign("school_list", $list);
        $this->assign("city", $city);
    }

}
?>

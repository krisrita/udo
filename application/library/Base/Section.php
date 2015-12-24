<?php

class Base_Section extends Base_Contr
{
    protected $sectionId = 0;
    protected $section = null;
    protected $course = null;
    protected $chapter = null;

    public function init()
    {   
        parent::init();
        $this->sectionId = (int)$this->get("section_id", 0);
        $this->sectionId = !$this->sectionId ? (int)$this->post("section_id", 0) : $this->sectionId;

        if ($this->sectionId == 0) {
            $this->redirect("/error/?errno=" . Common_Error::ERROR_PARAM);
        }


        $courseModel = new CourseModel();
        $this->section = $courseModel -> getSection($this->sectionId);
        $this->chapter = $courseModel -> getSection($this->section['parent_id']);
        $this->course = $courseModel -> getCourse($this->section['course_id']);
        $userModel = new UserModel();
        $this->teacher = $userModel->getTeacher($this->section['teacher_id']);

       //记录用户学习数据
       $courseModel-> updateUserSection($this->uid, $this->section['id'], $this->course['id']);
       //课程学习人数
       $this->incrCourseViewNum();
    }


    /**
     * 课程学习人数++
     */
    function incrCourseViewNum()
    {
        $controllers = array(
            "Section" => array("info"=>1, "answer" => 1),
        );
        
        if (isset($controllers[$this->controllerName][$this->actionName])) {      
           //课程学习人数
           $courseModel = new CourseModel();
           $courseModel -> incrViewNum($this->course['id']);
        }
    }


}
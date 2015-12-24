<?php
/**
 * Created by PhpStorm.
 * User: open
 * Date: 2015/5/29
 * Time: 22:25
 */

class DB_Haodu_CourseVideo extends DB_Haodu {
    protected $tbl_name = "xdf_course_video";
    protected $pri_key = "id";

    public function __construct()
    {
        parent::__construct();
    }
} 

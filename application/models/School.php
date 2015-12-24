<?php

class SchoolModel
{

    /**
     * 拥有学校的城市
     */
    function getCities()
    {
        $tbl = new DB_Haodu_School();
        $cities = $tbl -> fetchAll("city_id");

        if(!$cities) {
            return false;
        }

        $cityIds = $tbl -> columnRow($cities, "city_id");
        $tblRegion = new DB_Howdo_Region();
        $cities = $tblRegion -> fetchAll("region_id as id, region_name as name", "where region_id in (".implode(",", $cityIds).")");
        if (!$cities) {
            return false;
        }

        return $cities;
    }

    /**
     * 用户可以看到的学校
     */
    function getUserSchoolIds($uid)
    {
        $tbl = new DB_Haodu_SchoolUser();
        $list = $tbl->fetchAll("school_id", "where uid={$uid}");

        if(!$list) return false;

        return $tbl->columnRow($list, "school_id");
    }
    
    /**
     * 学校列表
     */
    function getSchoolList($cityId = 0, $uid = 0)
    {

        $schoolIds = $this->getUserSchoolIds($uid);        
        if ($schoolIds) {
            $where = "where (open_mode = 0 or id in (" . implode(",", $schoolIds) . "))";
        } else {            
            $where = "where open_mode = 0";
        }

        if($cityId > 0) {
            $where .= " and city_id={$cityId}";
        }

        $tbl = new DB_Haodu_School();
        $list = $tbl->fetchAll("id,name,title,image,flag", $where, "order by id desc");

        foreach ($list as &$school) {
            $school["image"] = Common_Config::getShoolImageUrl($school['image'], $school['flag']);
            unset($school['flag']);
        }
        unset($school);

        return $list;
    }

    /**
     * 查询一座学校
     */
    function getSchool($schoolId)
    {        
        $tbl = new DB_Haodu_School();
        return $tbl->fetchRow($schoolId, "id,name");
    }

    /*
     * 根据频道所在的地区混合年级进行过滤
     */
    /*
     * Array
(
    [id] => 8
    [mobile] => 18202565702
    [clientId] => 27af53e071f76987e84f3b3aa458a164
    [deviceToken] =>
    [name] => 服务器2
    [gender] => 0
    [email] => 18202565702@qq.com
    [qq] =>
    [province] => 0
    [city] => 0
    [area] => 0
    [title] =>
    [intro] =>
    [thumb] =>
    [className] =>
    [school] =>
    [score] => 0
    [amt] => 0
    [freezeAmt] => 0
    [withdrawAmt] => 0
    [withdrawFreezeAmt] => 0
    [entrances] => Array
        (
        )

    [mobiles] =>
    [ssotoken] => token52bebc0e-18a5-45b4-9e2b-3aa16db76ce1PGpridbI
)
     */
    function schoolFilter1($array){
        //对array['entrance']中的每一个数组循环遍历，过滤掉跟用户所在地区和年级不一致的频道

        //如果用户地区年级均有效
        if($array['province'] && $array['grade']){
            foreach ($array['entrances'] as $k=>$val){
                if (($val['city_id']!= $array['province'] && $val['city_id']!= $array['city'] && $val['city_id']!= $array['area']) ||
                    ($val['grade']<$array['grade'] || $val['grade']>= $array['grade']+10))
                    array_splice($array['entrances'],$k,1);
            }
        }

        //如果用户地区地区有效，年级无效。则不进行年级过滤
        if ($array['province'] && !$array['grade']){
            foreach ($array['entrances'] as $k=>$val){
                if ($val['city_id']!= $array['province'] && $val['city_id']!= $array['city'] && $val['city_id']!= $array['area'])
                    array_splice($array['entrances'],$k,1);
            }
        }

        //如果用户地区无效，年级有效，则不进行地区过滤
        if (!$array['province'] && $array['grade']){
            foreach ($array['entrances'] as $k=>$val){
                if ($val['grade']<$array['grade'] || $val['grade']>= $array['grade']+10)
                    array_splice($array['entrances'],$k,1);
            }
        }

        //如果用户地区年级均无效，不进行任何过滤
        return $array;
    }

    /*
     * schoolFilter的另一种写法
     */
    function schoolFilter($array){
        //print_r($array);
        //对array['entrance']中的每一个数组循环遍历，过滤掉跟用户所在地区和年级不一致的频道

        //print_r($array['entrances']);
        //如果apiUdoUrl为空，过滤
        $new_array1=[];
        foreach($array['entrances'] as $k=>$val){
            //print_r($val['apiUdoUrl']?1:0);
            if (!$val['apiUdoUrl']){
                //print_r($val);
/*                $result = array_splice($array['entrances'],$k,1);
                print_r($k);
                print_r($result);*/
                continue;
            }
            array_push($new_array1,$val);
        }

        $array['entrances'] = $new_array1;
        //print_r($array['entrances']);
        //如果用户地区有效，对频道列表中每一个频道，检查频道的地区信息是否无限制，如果没有限制，那么不过滤；如果有限制，过滤掉和用户所在地区不匹配的频道
        if($array['province']){
            foreach ($array['entrances'] as $k=>$val){
                //print_r($val);
                if ($val['cityId'] ){
                    if (($val['cityId']!= $array['provinceId'] && $val['cityId']!= $array['cityId'] && $val['cityId']!= $array['areaId']))
                    {array_splice($array['entrances'],$k,1); continue;}
                }

                /*if(!$val['apiUdoUrl'])
                    array_splice($array['entrances'],$k,1);*/
            }
        }

        //print_r($array);
        /*
         * 年级过滤算法：
         * 年级配置：
         * 1-9： 小学一到六年级；小学低年级（1-3）；小学高年级（4-6）；小学全年级
         * 11-16：初一-初三；初一初二；初二初三；初中全年级
         * 21-26：高一-高三；高一高二；高二高三；高中全年级
         * 0：不限制
         * 过滤掉
         */

/*        if($array['grade']){
            foreach ($array['entrances'] as $k=>$val){
                if ($val['grade'] ){
                    switch($array['grade']){
                        case 1:
                        case 2:
                        case 3:
                            if ($val['grade'] >= 11)
                                array_splice($array['entrances'],$k,1);
                            break;
                        case 4:
                        case 5:
                        case 6:
                            if ($val['grade'] >= 12 && $val['grade']!=14)
                                array_splice($array['entrances'],$k,1);
                            break;
                        case 11:
                        case 12:
                            if ($val['grade'] >= 21 || $val['grade']<=10)
                                array_splice($array['entrances'],$k,1);
                            break;
                        case 13:
                            if (($val['grade'] >= 22 && $val['grade']!=24) || $val['grade']<=10)
                                array_splice($array['entrances'],$k,1);
                            break;
                        case 21:
                        case 22:
                        case 23:
                            if ( $val['grade']<=20)
                                array_splice($array['entrances'],$k,1);
                            break;
                    }
                }
            }
        }*/

        //$array['grade'] = "小学三年级";
        echo strstr($array['grade'],"小");
        if ($array['grade']){
            foreach ($array['entrances'] as $k=>$val) {
                if($val['grade']){
                    switch($array['grade']){
                        case strstr($array['grade'],'小'):
                            if ($val['grade'] >= 11 && $val['grade']!=14)
                                array_splice($array['entrances'],$k,1);
                            break;
                        case strstr($array['grade'],'初'):
                            if (($val['grade'] >= 21 && $val['grade']!=24) || $val['grade']<=10)
                                array_splice($array['entrances'],$k,1);
                            break;
                        case strstr($array['grade'],'高'):
                            if ( $val['grade']<=20)
                                array_splice($array['entrances'],$k,1);
                            break;
                    }
                }
            }
        }

        //如果用户地区年级均无效，不进行任何过滤
        return $array;
    }

    function schoolOrder($array){
/*        $url = Common_Config::SSO_ENTRY_URL;
        $entry = [];
        $index = 0;
        $rows = 20;
        for ($page = 1; $index == 0 || ($index>0 && array_key_exists($index,$entry)) ;$page++){
            $post_data = array("page"=>$page,"rows"=>$rows);
            //print_r($post_data);
            $cl = new Common_Curl();

            $entry = array_merge($entry,$cl->request($url, $post_data));
            //print_r($entry);
            $index = $page * $rows - 1;
        }*/

/*        $url_user = Common_Config::SSO_USER_ENTRY_URL;
        $cl = new Common_Curl();
        print_r($array);
        $entry = [];
        foreach ($array['entrances'] as $k => $val){
            $user_entry = [];
            $post_data = array("domainId" => $val['id']);
            $user_entry = $cl->request($url_user,$post_data);
            //print_r($user_entry);
            foreach ($user_entry as $l => $value)
                if(!$value['isPublic'])
                    array_push($entry,$val['id']);
        }

        print_r($entry);*/
        //print_r($array);
        $new_array=[];
        foreach ($array['entrances'] as $k=>$val){
            //测试代码
            /*if($val['id']==11){
                $array['entrances'][$k]['apiBaseUrl']="test";
                $val['apiBaseUrl']="test";
                $array['entrances'][$k]['apiUdoUrl']="";
                $val['apiUdoUrl']="";
                print_r("yes"."<br>");
            }*/
            //print_r($k.":  ");
            if( $val['apiBaseUrl'] && $val['apiUdoUrl']==""){
                continue;
                //print_r("yesy".$k."  ");
            }
            array_push($new_array,$array['entrances'][$k]);
        }
        //print_r($new_array);
        $array['entrances'] = $new_array;
        $pri_entry = [];
        $public_entry = [];
        $private_entry = [];
        $public_forbid = [];
        $private_forbid = [];

        foreach($array['entrances'] as $k=>$val){
            //非公开且授权的频道排序最靠前
            if (!$val['isPublic'] && $val['isAuthorized']){
                //array_push($entry,$val['id']);
                array_push($private_entry,$val);
                //print_r($val['id']." ");
            }
            //重点公开频道排序第二靠前
            else if(($val['id']==2748 || $val['id']==2753 || $val['id']==2746 ||$val['id']==11)&&$val['isPublic']){
                array_push($pri_entry,$val);
                continue;
            }
            //公开授权频道排序其次
            else if ($val['isPublic'] && $val['isAuthorized']){
                array_push($public_entry,$val);
            }
            //私有或公开未授权频道排序在最后
            else if ($val['isPublic'] && !$val['isAuthorized'])
                array_push($public_forbid,$val);
            else
                array_push($private_forbid,$val);
        }
        //$new_array = $pri;
        $new_array = array_merge($private_entry,$pri_entry,$public_entry,$public_forbid,$private_forbid);

        //print_r($array);
        return $new_array;
    }

    function getBanner(){
        $tbl = new DB_Udo_Banner();
        //$banner = $tbl -> fetchAll("id,logo,bannerType,bannerUrl,school,apiUdoUrl","where isValid=1","order by `order` asc");
        $banner = $tbl -> fetchAll("id,logo,bannerType,bannerUrl,school,apiUdoUrl,customerName,customerTitle","where isValid=1","order by `order` asc");
        //print_r($banner?$banner:"null");
        foreach ($banner as $k=>$val){
            $banner[$k]['logo'] = Common_Config::SITE_DOMAIN.$val['logo'];
        }
        return $banner;
    }

    function bannerStatistics($uid,$bannerId){
        $tbl = new DB_Udo_BannerStatistics();
        $insert = $tbl -> insert(array("userId"=>$uid,"bannerId"=>$bannerId,"createTime"=>time()));
        return $insert;
    }

    function schoolStatistics($uid,$schoolId,$isAuthorized,$type){
        $tbl = new DB_Udo_SchoolStatistics();
        $insert = $tbl->insert(array("userId"=>$uid,"schoolId"=>$schoolId,"isAuthorized"=>$isAuthorized,"createTime"=>time(),"type"=>$type));
        return $insert;
    }

    /*
     * 获取首页编辑推荐
     */
    function getRec($uid,$actionId){
        $tblRec = new DB_Udo_SchoolRec();
        $tblResource = new DB_Sso_Resource();
        $tblEntrance = new DB_Sso_Entrance();
        $tblLog = new DB_Udo_RecLog();
        $tradeModel = new TradeModel();

        if($actionId == 1){
            //首次获取最新更新的前三条编辑推荐
            $recs = $tblRec -> fetchLimit("id,entranceId,resourceId,resourceType,courseType","where isValid = 1","order by createTime desc",1,3);
            $page = 1;
        }
        else
        {
            //非首次请求按照历史请求来分页，如何获取当前应当请求的是第几页
            $latestLog = $tblLog -> scalar("*","where userId = {$uid}","order by createTime desc");
            //print_r($latestLog);
            //如果最近一次请求是首次请求
            if( $latestLog['actionId'] == 1){
                $recs = $tblRec -> fetchLimit("id,entranceId,resourceId,resourceType,courseType","where isValid = 1","order by createTime desc",2,3);
                $page = 2;
            }

            else{
                //print_r($latestLog['page']);
                $page = ($latestLog['page']+1)>3?1:($latestLog['page']+1);
                //print_r($page);
                $recs = $tblRec -> fetchLimit("id,entranceId,resourceId,resourceType,courseType","where isValid = 1","order by createTime desc",$page,3);
            }

        }

        //将数据库查询返回的数组中的字段提取出来专门形成一个数组
        $resourceIds = $tblRec->columnRow($recs,"resourceId");
        //在resource表中找出id存在于推荐表中的项目
        $resource = $tblResource -> fetchAll("name,type,local_id,local_type,entrance_id,parent_id","where id in (".implode(",",$resourceIds).")");

        //提取出所有存在于推荐表中的频道信息
        $entranceIds = $tblRec->columnRow($recs,"entranceId");
        $entrance = $tblEntrance->fetchAll("id,customer_name,customer_title,logo,api_udo_url" , "where id in (".implode(",",$entranceIds).")");

        //print_r($recs);
        $recommend = [];
        //返回相关信息
        foreach ($recs as $rec){
            //如果资源是学校级,返回学校的相关信息

            //先找到推荐的频道信息
            foreach( $entrance as $k=>$entr){
                if($entr['id'] == $rec['entranceId']){
                    $entr['customerName'] = $entr['customer_name'];
                    unset($entr['customer_name']);
                    $entr['customerTitle'] = $entr['customer_title'];
                    unset($entr['customer_title']);
                    $entr['apiUdoUrl'] = $entr['api_udo_url'];
                    unset($entr['api_udo_url']);
                    //print_r($entr['id']);
                    $entr['isSubscribed'] = $this->getIfSub($entr['id'],$uid);
                    //print_r($entr['isSubscribed']);
                    break;
                }
            }

            //找到当前推荐项目的resource资源信息
            foreach ($resource as $res){
                if($res['entrance_id'] == $rec['entranceId']){
                    break;
                }

            }
            //如果推荐的是频道类型
            if ( $rec['resourceType'] == Common_Config::UDO_RESOURCE_SCHOOL){
                $array = array("recId"=>$rec['id'],"courseId"=>0,"courseName"=>"","courseType"=>$rec['courseType']);
                $price = $this->getSchoolPrice($rec['entranceId'],$uid);
                //print_r($recommend);
            }

            //如果当前推荐是课程类型，那么追加课程信息
            else{
                $array = array("recId"=>$rec['id'],"courseId"=>$res['local_id'],"courseName"=>$res['name'],"courseType"=>$rec['courseType']);
                $price = $this->getCoursePrice($rec['resourceId'],$uid);
                //print_r($price);
            }

            //将推荐类型，推荐项目的基础信息，频道信息融合在一个数组中
            $entr = array_merge(array("resourceType"=>$rec['resourceType']),$array,$entr,$price);
            array_push($recommend,$entr);
        }

        //print_r($recommend);
        return array("rec"=>$recommend,"page"=>$page);

    }

    /*
     * 首页编辑推荐动作日志
     */
    function recLog($userId,$actionId,$rec,$page){
        $tblLog = new DB_Udo_RecLog();
        $tbl = new DB_Udo_SchoolStatistics();
        $tblRec = new DB_Udo_SchoolRec();
        foreach ($rec as $r){
            $insert = $tblLog->insert(array("userId"=>$userId,"actionId"=>$actionId,"recId"=>$r['recId'],"page"=>$page,"createTime"=>time()));
            //如果是通过推荐进入频道，除了写入编辑推荐日志，也要写入频道进入日志
            if($actionId == 3){
                $schoolId = $tblRec->scalar("entranceId","where id = {$r['recId']}");
                $insert = $tbl->insert(array("userId"=>$userId,"schoolId"=>$schoolId,"isAuthorized"=>1,"createTime"=>time()));
            }
        }
    }

    /*
     * 订阅/取消订阅频道
     */
    function subscribe($uid,$actionId,$schoolId){
        $tblSub = new DB_Udo_SchoolSubscribe();
        $tblLog = new DB_Udo_SubLog();
        $tblSta = new DB_Udo_SchoolStatistics();
        $type = 2;

        //先获取用户是否订阅频道的信息
        $sub = $tblSub->scalar("*","where userId = {$uid} and schoolId = {$schoolId} ");
        //如果用户选择订阅频道，执行插入动作
        if ($actionId == 1){
            //如果用户已经订阅了该频道，返回3
            if ($sub && $sub['isValid']){
                $tblLog -> insert(array("userId"=>$uid,"actionId"=>$actionId,"schoolId"=>$schoolId,"status"=>3,"createTime"=>time()));
                //需客户端处理错误信息
                return 3;
            }

            //如果用户之前取消订阅了该频道，重新恢复订阅信息,并且写入订阅日志
            else if($sub && !$sub['isValid']){
                //$update = $tblSub -> update($sub['id'],array("userId"=>$uid,"schoolId"=>$schoolId,"createTime"=>time(),"isValid"=>1));
                $update = $tblSub->query("UPDATE udo_school_subscribe set isValid = 1 where id ={$sub['id']}");
                if($update){
                    $tblLog -> insert(array("userId"=>$uid,"actionId"=>$actionId,"schoolId"=>$schoolId,"status"=>2,"createTime"=>time()));
                    $tblSta->insert(array("userId"=>$uid,"schoolId"=>$schoolId,"isAuthorized"=>1,"createTime"=>time(),"type"=>$type));
                    return 2;
                }
                else{
                    $tblLog -> insert(array("userId"=>$uid,"actionId"=>$actionId,"schoolId"=>$schoolId,"status"=>5,"createTime"=>time()));
                    return 7;
                }
            }

            //如果用户没有订阅过该频道，插入频道订阅信息，并且写入日志
            else{
                $insert = $tblSub -> insert(array("userId"=>$uid,"schoolId"=>$schoolId,"createTime"=>time(),"isValid"=>1));
                if($insert){
                    $tblLog -> insert(array("userId"=>$uid,"actionId"=>$actionId,"schoolId"=>$schoolId,"status"=>1,"createTime"=>time()));
                    $tblSta->insert(array("userId"=>$uid,"schoolId"=>$schoolId,"isAuthorized"=>1,"createTime"=>time(),"type"=>$type));
                    return 1;
                }
                else{
                    $tblLog ->insert(array("userId"=>$uid,"actionId"=>$actionId,"schoolId"=>$schoolId,"status"=>7,"createTime"=>time()));
                    return 7;
                }
            }

        }

        //如果用户选择取消订阅
        elseif($actionId == 2){
            //如果用户订阅了该频道,直接取消
            if($sub && $sub['isValid']){
                //$update = $tblSub -> update($sub['id'],array("userId"=>$uid,"schoolId"=>$schoolId,"createTime"=>time(),"isValid"=>0));
                $update = $tblSub->query("UPDATE udo_school_subscribe set isValid = 0 where id ={$sub['id']}");
                if($update){
                    $tblLog ->insert(array("userId"=>$uid,"actionId"=>$actionId,"schoolId"=>$schoolId,"status"=>4,"createTime"=>time()));
                    return 4;
                }
                else{
                    $tblLog ->insert(array("userId"=>$uid,"actionId"=>$actionId,"schoolId"=>$schoolId,"status"=>8,"createTime"=>time()));
                    return 8;
                }
            }

            //如果用户的频道订阅信息已经无效
            elseif ($sub && !$sub['isValid']){
                $tblLog ->insert(array("userId"=>$uid,"actionId"=>$actionId,"schoolId"=>$schoolId,"status"=>5,"createTime"=>time()));
                return 5;
            }

            //如果用户没有订阅过该频道
            else{
                $tblLog ->insert(array("userId"=>$uid,"actionId"=>$actionId,"schoolId"=>$schoolId,"status"=>8,"createTime"=>time()));
                return 8;
            }
        }
    }

    /*
     * update test
     */
    function testUpdate(){
        $tbl = new DB_Udo_News();
        $update = $tbl->update(5,array('isValid'=>0));
        $query = $tbl->query("UPDATE udo_news set isValid = 0 where id =5");
        return $query;
    }

    /*
     * 筛选用户已经订阅的频道
     * 该接口后面应该改为直接从数据库读取
     */

    function getSubscribe($array,$uid,$type){
        $tblSub = new DB_Udo_SchoolSubscribe();
        $tblSta = new DB_Udo_SchoolStatistics();

        //获取到所有已订阅的频道id
        $subSchools = $tblSub -> fetchAll("id,schoolId","where userId = {$uid} and isValid = 1");
        //print_r($subSchools);
        $sub_array = [];

        //根据频道操作日志表中的最近时间排序已知的id(需要确保)
        $subIds = $tblSub ->columnRow($subSchools,"schoolId");
        //print_r($subIds);
        //$sta = $tblSta->fetchAll("id,schoolId","where userId = {$uid} and schoolId in (".implode(",",$subIds).") group by schoolId","order by createTime desc");
        $sta = $tblSta->fetchAll("id,schoolId","where userId = {$uid} and schoolId in (".implode(",",$subIds).") ","order by createTime desc");
        //$sta = $tblSta->fetchAll("id,schoolId","where userId = {$uid} group by schoolId","order by createTime desc");
        //$stas =  $tblSta->fetchAll("id,schoolId","where userId = {$uid}","order by createTime desc");
        $staIds = $tblSta -> columnRow($sta,"schoolId");
        //print_r($staIds);
        //print_r($stas);
        $orderIds = array_unique($staIds);
        //print_r($orderIds);
        //print_r(count($orderIds));
        //根据id过滤已订阅信息
/*        foreach ($array['entrances'] as $k=>$value){
            foreach ($orderIds as $l){

            }
            if (count($sub_array) == count($orderIds))
                break;
        }*/

        foreach ($orderIds as $l){
            foreach ($array as $k=>$value) {
                if ($value['id'] == $l)
                    array_push($sub_array,$value);
            }
            if (count($sub_array) == count($orderIds))
                break;
        }

        //如果是获取前三个订阅信息
        if($type == 2)
            $sub_array = array_slice($sub_array,0,3);
        //print_r($sub_array);
        return $sub_array;
    }

    /*
     *根据频道和用户获取是否已订阅信息
     */

    function getIfSub($schoolId,$uid){
        $tblSub = new DB_Udo_SchoolSubscribe();
        $ifSub = $tblSub->scalar("*","where userId = {$uid} and schoolId = {$schoolId} and isValid =1");
        if($ifSub)
            return 1;
        else
            return 0;
        //return $ifSub;
    }

    /*
     * 获取资讯列表
     */
    function getNews($actionId,$previousId,$pageSize){
        $tblNews = new DB_Udo_News();
        $previousTime = $tblNews->scalar("*","where id = {$previousId}");
        $str = $previousTime?"and createTime < {$previousTime['createTime']}":"";
        //print_r($previousTime);

        //首次请求
        if($actionId == 1){
            $news = $tblNews->fetchLimit("id,title,logo,url,createTime","where isValid = 1","order by createTime desc",1,5);
        }
        //非首次请求，
        else{
            $news = $tblNews->fetchLimit("id,title,logo,url,createTime","where isValid = 1 ".$str,"order by createTime desc",1,$pageSize);
        }
        foreach ($news as $k=>$val){
            $news[$k]['logo'] = Common_Config::SITE_DOMAIN.$val['logo'];
            $news[$k]['createTime'] = date("Y/m/d",$news[$k]['createTime']);
        }
        /*print_r($previousTime['createTime']);
        print_r($news);*/
        return $news;
    }

    /**
     * 记录用户的资讯访问日志
     */
    function newsLog($uid,$newsId){
        $tblLog = new DB_Udo_NewsLog();
        $insert = $tblLog->insert(array("newsId"=>$newsId,"userId"=>$uid,"createTime"=>time()));
        return $insert;
    }

    /*
     * 对频道的name和title进行模糊搜索
     */
    function searchSchool($keyword,$ssotoken){

        $tblLog = new DB_Udo_SearchLog();
        $userModel = new UserModel();
        $uid = $userModel->getUserId($ssotoken);
        //print_r($keyword);
        //先进行结果查询，将查询出来的id记录下来
        $tblEntrance = new DB_Sso_Entrance();
        //记录结果id的数组
        $result = [];
        $resultIds = [];

        //关键字搜索的长度限制
        //获取输入的关键字的字符串长度
       /* $length = strlen($keyword);
        for($i = $length;$i >=1 ;$i--){
            //对关键词进行拆分
            $split_array = str_split($keyword,$i);
            foreach ($split_array as $k=>$value){
                //对于每一个截断后的结果进行搜索
                $name = $tblEntrance->fetchAll("id","where customer_name like '%{$value}%' or customer_title like '%{$value}%' ");
                $result = array_merge($result,$name);
            }

        }
        $resultIds = $tblEntrance->columnRow($result,"id");
        print_r(array_unique($resultIds));
        print_r(str_split("外语"));
        print_r(preg_split("/[\s,]+/","外语 教学,研究"));

        $tempaddtext="php对UTF8字体串进行单字分割返回数组";
        //$cind代表的是字符位移
        $cind = 0;
        $arr_cont = array();
        for ($i = 0; $i < strlen($tempaddtext); $i++) {
            if (strlen(substr($tempaddtext, $cind, 1)) > 0) {
            if (ord(substr($tempaddtext, $cind, 1)) < 192) {
            if (substr($tempaddtext, $cind, 1) != " ") {
                array_push($arr_cont, substr($tempaddtext, $cind, 1));}
            $cind++;}
            elseif(ord(substr($tempaddtext, $cind, 1)) < 224) {
            array_push($arr_cont, substr($tempaddtext, $cind, 2));
                $cind+=2;} else {array_push($arr_cont, substr($tempaddtext, $cind, 3));$cind+=3;}}}
        print_r($arr_cont);*/

        $result = $tblEntrance->fetchAll("id","where customer_name like '%{$keyword}%' or customer_title like '%{$keyword}%' ");

        $resultIds = $tblEntrance->columnRow($result,'id');

        if(!$resultIds){
            $tblLog->insert(array("userId"=>$uid,"keyword"=>$keyword,"result"=>-1,"createTime"=>time()));
            return -1;
        }

        //print_r($resultIds);

        $result_array = [];
        //从sso获取所有频道
        $url = Common_Config::SSO_SCHOOL_URL;
        $post_data = array("ssotoken"=>$ssotoken);
        $cl = new Common_Curl();
        $array = $cl->request($url, $post_data);

        //针对搜索结果，返回频道的基础信息
        foreach ($resultIds as $l){
            foreach ($array['entrances'] as $k=>$val) {
                if ($val['id'] == $l){
                    $price = $this->getSchoolPrice($l,$uid);
                    $val = array_merge($val,array("isSubscribed"=>($this->getIfSub($val['id'],$uid))?1:0),$price);
                    $index = 0;
                    //过滤掉apiBaseUrl项目
                    while($key = key($val)){
                        if($key == "apiBaseUrl"){
                            array_splice($val,$index,1);
                            break;
                        }
                        $index++;
                        next($val);
                    }
                    array_push($result_array,$val);
                }

            }
            if (count($result_array) == count($resultIds))
                break;
        }

        if(!$result_array){
            $tblLog->insert(array("userId"=>$uid,"keyword"=>$keyword,"result"=>-1,"createTime"=>time()));
            return -1;
        }



        $tblLog->insert(array("userId"=>$uid,"keyword"=>$keyword,"result"=>count($result_array),"resultString"=>implode(',',$resultIds),"createTime"=>time()));
        //print_r($result_array);
        return $result_array;
    }

    /*
     * 获取频道的定价和当前用户是否购买的信息
     */
    function getSchoolPrice($schoolId,$uid){
        $tblPrice = new DB_Udo_SchoolPrice();
        $tblBought = new DB_Udo_UserBought();
        $tblResource = new DB_Sso_Resource();
        $bought = 0;

        //查找出频道的定价信息
        $price = $tblPrice->scalar("priceType,price","where resourceId = {$schoolId}");

        //没有查询到定价信息的频道一律按照免费处理
        if(!$price)
            $price = array("priceType"=>Common_Config::UDO_PRICETYPE_FREE,"price"=>0);

        //查找用户的购买信息
        //原则上免费课程不能加入购买表中
        $isBought = $tblBought->fetchAll("resourceId","where schoolId = {$schoolId} and resourceType = 2 and userId = {$uid}");
        //print_r($isBought);
        //查找该频道下的所有非免费课程
        $resource = $tblResource->fetchAll("id","where entrance_id = {$schoolId} and type = 6 and price_type <> 3 and enabled = 1");

        //如果定价免费
        if($price['priceType'] == 3)
            $bought = 1;
            //如果定价不是免费，需了解是否所有课程都已经购买过
        else
        {
            $boughtCount = 0;
            foreach($resource as $k=>$value){
                foreach($isBought as $l=>$val){
                    if ($value['id'] == $val['resourceId']){
                        $boughtCount++;
                        break;
                    }
                }
            }

            //考虑到频道中的课程可能会有更新，所以不能以当前频道的购买状态作为频道是否购买的依据，
            //而是要以非免费课程和已购买课程的数量是否相等作为依据
            if($boughtCount == count($resource))
                $bought = 1;
        }

        return array("price"=>$price['price'],"priceType"=>$price['priceType'],"isBought"=>$bought);
    }

    /*
     * 获取课程的定价和当前用户是否购买的信息
     * 形参课程id指的是公共云上的id
     */
    function getCoursePrice($courseId,$uid){
        $tblResource = new DB_Sso_Resource();
        $tblUserBought = new DB_Udo_UserBought();
        $courseType = Common_Config::UDO_RESOURCE_COURSE;
        $priceInfo = $tblResource->scalar("cur_price,price_type","where id = {$courseId} and enabled = 1");
        $isBought = $tblUserBought->scalar("id","where resourceType = {$courseType} and userId = {$uid} and resourceId = {$courseId}");
        //print_r($isBought);
        return array("price"=>$priceInfo['cur_price'],"priceType"=>$priceInfo['price_type'],"isBought"=>$isBought?1:0);
    }

    /*
     * 返回课程的购买信息
     */
    function courseBought($courseId,$uid){
        $tblBought = new DB_Udo_UserBought();
        //$bought = $tblBought->scalar()
    }

    /*
     * 获取单个频道的信息
     */
    function getSingleSchool($uid,$schoolId,$array){
        $result = [];
        foreach($array as $k=>$value){
            //print_r($value['id']);
            if($value['id'] == $schoolId){
                array_push($result,$value);
                return $result;
                break;
            }
        }
        return -1;
    }

    /*
     * 根据id获取课程的基础信息：名称信息
     */
    function getSingleCourse($courseId){
        $tblResource = new DB_Sso_Resource();
        $courseType = Common_Config::PUBLIC_COURSE_TYPE;
        $course = $tblResource->scalar("name","where id = {$courseId} and type = $courseType");
        return $course;
    }
    /*
     * 根据课程id获取所在的频道信息
     */
    function getSchoolByCourse($courseId){
        $tblResource = new DB_Sso_Resource();
        $tblEntrance = new DB_Sso_Entrance();
        $entranceId = $tblResource->scalar("entrance_id","where id = {$courseId}");
        $entrance = $tblEntrance->scalar("customer_name,customer_title","where id = {$entranceId['entrance_id']}");
        return $entrance;
    }

}
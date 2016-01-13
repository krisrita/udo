<?php
/**
 * Created by PhpStorm.
 * User: Kris
 * Date: 2015/11/9
 * Time: 15:25
 */

class TradeModel
{
    function getPrice1($userId,$domainId){
    $url = Common_Config::SSO_USER_RESOURCE;
    $post_data = array ("domainId" => $domainId,"userId" => $userId);
    $cl= new Common_Curl();
    $array = $cl->request($url,$post_data);

    //print_r($array);
    $tblResource = new DB_Sso_Resource();

    //循环遍历$array，并根据id获取每一个节点的定价信息，追加在节点后面
    foreach ($array as $k=>$course){
        //对于每一个父节点，根据id判断是否”已购买“
        $priceInfo = $tblResource -> scalar("price_type,cur_price,ori_price","where id = {$course['id']}");
        //print_r($priceInfo);
        $array[$k] = (array_merge($array[$k],$priceInfo));

        //print_r($array[$k]['children']);
        foreach ($array[$k]['children'] as $m => $csParent){
            $priceInfoRaw = $tblResource -> scalar("price_type,cur_price,ori_price","where id = {$csParent['id']}");
            $priceInfo = array("price"=>$priceInfoRaw['cur_price'],"priceType"=>$priceInfoRaw['price_type']);
            $array[$k]['children'][$m] = (array_merge($array[$k]['children'][$m],$priceInfo,array("isBought" => 0)));
            foreach ($array[$k]['children'][$m]['children'] as $n => $csChildren){
                $priceInfoRaw = $tblResource -> scalar("price_type,cur_price,ori_price","where id = {$csChildren['id']}");
                $priceInfo = array("price"=>$priceInfoRaw['cur_price'],"priceType"=>$priceInfoRaw['price_type']);
                $array[$k]['children'][$m]['children'][$n] = (array_merge($array[$k]['children'][$m]['children'][$n],$priceInfo,array("isBought" => 0)));
            }

        }
    }
    //print_r($array);
    return $array;
}

    function getPrice2($userId,$domainId,$parentId,$localType){

        //首先从SSO获取到云滴请求的资源列表
        $url = Common_Config::SSO_USER_SINGLE_RESOURCE;
        $post_data = array ("domainId" => $domainId,"userId" => $userId,"parentId"=>$parentId,"localType"=>$localType);
        $cl= new Common_Curl();
        $array = $cl->request($url,$post_data);

        $schoolModel = new SchoolModel();
        $tblResource = new DB_Sso_Resource();
        $tblBought = new DB_Udo_UserBought();
        $bought = [];
        $isBought = 0;

        //print_r($array);
        //循环遍历$array，并根据id获取每一个节点的定价信息，追加在节点后面
        foreach ($array as $k=>$course){

            //对于每一个父节点，根据id判断是否”已购买“
            $priceInfo = $tblResource -> scalar("price_type,cur_price,ori_price","where id = {$course['id']}");

            //接下来获取当前资源用户是否已经购买
            //如果资源是课程类型，直接读取
            if($localType == Common_Config::UDO_LOCAL_COURSE_TYPE){
                $bought = $tblBought->scalar("id","where userId = {$userId} and schoolId = {$domainId} and resourceId = {$course['id']} and resourceType = 2");
            }
            //如果资源是章类型,获取父节点-课程节点的购买信息
            else if($localType == Common_Config::UDO_LOCAL_CHAPTER_TYPE){
                $bought =  $tblBought->scalar("id","where userId = {$userId} and schoolId = {$domainId} and resourceId = {$parentId} and resourceType = 2");
            }
            //如果资源是节类型，parentId是章，获取该章的父节点
            else if($localType == Common_Config::UDO_LOCAL_SECTION_TYPE){
                $chapterId = $tblResource->scalar("parent_id","where entrance_id = {$domainId} and id={$parentId}");
                $bought =  $tblBought->scalar("id","where userId = {$userId} and schoolId = {$domainId} and resourceId = {$chapterId['parent_id']} and resourceType = 2");
            }
            if($bought)
                $isBought = 1;
            $array[$k] = (array_merge($array[$k],array("priceType"=>$priceInfo['price_type'],"price"=>$priceInfo['cur_price'],"isBought"=>$isBought)));
            //print_r($array[$k]['children']);
        }

        //print_r($array);
        return $array;
    }

    /*
     * 云滴调用，获取课程，章节各层的定价信息
     */
    function getPrice($userId,$domainId,$parentId,$localType){

        //首先从SSO获取到云滴请求的资源列表
        $url = Common_Config::SSO_USER_SINGLE_RESOURCE;
        $post_data = array ("domainId" => $domainId,"userId" => $userId,"parentId"=>$parentId,"localType"=>$localType);
        $cl= new Common_Curl();
        $array = $cl->request($url,$post_data);
        //print_r($array);
        $schoolModel = new SchoolModel();
        $tblResource = new DB_Sso_Resource();
        $tblBought = new DB_Udo_UserBought();
        $bought = [];

        //循环遍历$array，并根据id获取每一个节点的定价信息，追加在节点后面
        foreach ($array as $k=>$course){
            $isBought = 0;
            //对于每一个父节点，根据id判断定价类型，现价和原价
            $priceInfo = $tblResource -> scalar("price_type,cur_price,ori_price","where id = {$course['id']}");
            //如果资源没有定价，统一按照免费类型处理
            if(!$priceInfo['price_type'])
                $priceInfo['price_type'] = Common_Config::UDO_PRICETYPE_FREE;

            //接下来获取当前资源用户是否已经购买
            //如果资源是课程类型，直接读取
            if($localType == Common_Config::UDO_LOCAL_COURSE_TYPE){
                $bought = $tblBought->scalar("id","where userId = {$userId} and schoolId = {$domainId} and resourceId = {$course['id']} and resourceType = 2");

                //如果找到了购买信息或者资源的定价类型是免费，那么直接设置购买信息为已购买
                if($bought || $priceInfo['price_type'] == 3)
                    $isBought = 1;
                else
                    $isBought = 0;

                $array[$k] = (array_merge($array[$k],array("priceType"=>$priceInfo['price_type'],"price"=>$priceInfo['cur_price'],"isBought"=>$isBought)));
            }
            //如果资源是章类型,获取父节点-课程节点的购买信息
            else if($localType == Common_Config::UDO_LOCAL_CHAPTER_TYPE){
                $bought =  $tblBought->scalar("id","where userId = {$userId} and schoolId = {$domainId} and resourceId = {$parentId} and resourceType = 2");
                if($bought || $priceInfo['price_type'] == 3)
                    $isBought = 1;
                else
                    $isBought = 0;
                //获取到该章的购买信息后，再次调用SSO权限接口，获取该章下的节列表，即节的定价信息
                $post_data = array ("domainId" => $domainId,"userId" => $userId,"parentId"=>$course['id'],Common_Config::UDO_LOCAl_SECTION_TYPE);
                $arraySection = $cl->request($url,$post_data);
                foreach($arraySection as $l=>$section){
                    $arraySection[$l] = array_merge($arraySection[$l],array("priceType"=>$priceInfo['price_type'],"price"=>$priceInfo['cur_price'],"isBought"=>$isBought));
                }
                //返回章的定价信息和其子节点孩子的定价信息
                $array[$k] = array_merge($array[$k],array("priceType"=>$priceInfo['price_type'],"price"=>$priceInfo['cur_price'],"isBought"=>$isBought,"children"=>$arraySection));
            }
            //print_r($array[$k]['children']);
        }

        //print_r($array);
        return $array;
    }

    /*
     * 获取用户当前可购买的资源信息
     * 如果用户从筛选条件进入，需要客户端找到已购课程和筛选结果的交集
     */
    function getAvailableRes($uid,$domainId){
        $schoolModel = new SchoolModel();
        $tblResource = new DB_Sso_Resource();
        $tblBought = new DB_Udo_UserBought();
        $availableArray = [];

        //首先从SSO获取到云滴请求的资源列表
        $url = Common_Config::SSO_USER_SINGLE_RESOURCE;
        $post_data = array ("domainId" => $domainId,"userId" => $uid,"parentId"=>0,"localType"=>Common_Config::UDO_LOCAL_COURSE_TYPE);
        $cl= new Common_Curl();
        $array = $cl->request($url,$post_data);

        //获取频道的定价信息
        $schoolPrice = $schoolModel->getSchoolPrice($domainId,$uid);
        $totalPrice = 0;
        $hasBought = 0;
        $children = [];
        foreach ($array as $k=>$course){
            //对于每一个父节点，根据id判断定价类型，现价和原价
            $priceInfo = $tblResource -> scalar("price_type,cur_price,ori_price","where id = {$course['id']}");
            //接下来获取当前资源用户是否已经购买
            $bought = $tblBought->scalar("id","where userId = {$uid} and schoolId = {$domainId} and resourceId = {$course['id']} and resourceType = 2");
            if($bought || $priceInfo['price_type'] == 3){
                if($bought)
                    $hasBought = 1;
                continue;
            }
            else{
                array_push($children,array("courseId"=>$course['id'],"priceType"=>$priceInfo['price_type'],"price"=>$priceInfo['cur_price'],
                    "localId"=>$this->getLocalId($course['id'],$domainId)['local_id']));
                $totalPrice+=$priceInfo['cur_price'];
            }

        }
        if(!$children)
            return -1;

        //生成返回列表
        //如果用户购买过频道的课程，那么不再按折扣价格计算

        if($hasBought)
            $price = $totalPrice;
        else
            $price = $schoolPrice['price'];
        $availableArray = array("schoolId"=>$domainId,"priceType"=>$schoolPrice['priceType'],"price"=>$price);
        return array_merge($availableArray,array("children"=>$children));

    }

    /*
     * 获取资源的父级id
     */
    function getParentId($localId,$domainId,$localType){
        $tblResource = new DB_Sso_Resource();
        $parent = $tblResource->scalar("parent_id","where local_id = {$localId} and entrance_id = {$domainId} and local_type = {$localType}");
        return $parent;
    }

    /*
     * 获取本地资源的sso id
     */
    function getSsoId($localId,$domainId,$localType){
        $tblResource = new DB_Sso_Resource();
        $type = Common_Config::UDO_LOCAL_COURSE_TYPE;
        $ssoId = $tblResource->scalar("id","where local_id = {$localId} and entrance_id = {$domainId} and local_type = {$type}");
        //print_r($parent);
        return $ssoId;
    }

    /*
     * 获取sso资源的localId
     */
    function getLocalId($ssoId,$domainId){
        $tblResource = new DB_Sso_Resource();
        $localId = $tblResource->scalar("local_id","where id = {$ssoId} and entrance_id = {$domainId}");
        return $localId;
    }

    /*
     * 根据充值项目的id获取充值U币的人民币信息
     */
    function getCoinMoney($coinId){
        $tblCoinInfo = new DB_Udo_CoinInfo();
        $money  = $tblCoinInfo->scalar("price","where id = {$coinId}");
        return $money;
    }

    /*
     * 获取频道的价格基础信息
     * 可以按照以下参数筛选
     */
    function getSchoolPrice($priceType=0,$isPriced=2,$isPublic=2,$keyword="",$schoolId=0){
        $tblEntrance = new DB_Sso_Entrance();
        $tblSchoolPrice = new DB_Udo_SchoolPrice();
        $schoolModel = new SchoolModel();

        //构造筛选条件
        $where = "where open_status = 1";
        if($keyword)
            $where .= " and (customer_name like '%{$keyword}%' or customer_title like '%{$keyword}%')";
        if($schoolId)
            $where .= " and id ={$schoolId}";
        if($isPublic !=2 )
            $where .= " and is_public = {$isPublic}";

        //默认按照频道发布的先后顺序排
        $entrance = $tblEntrance->fetchAll("id,customer_name,customer_title,is_public,created_on",$where,"order by created_on desc");
        $resultArr = [];
        foreach($entrance as $k=>$val){
            $price = $tblSchoolPrice->scalar("resourceId,priceType,price","where resourceId = {$val['id']}");
            if(!$price){
                $val['priceType'] = 0;
                $val['price'] = 0;
            }
            else{
                $val['price'] = $price['price'];
                $val['priceType'] = $price['priceType'];
            }

            //根据定价类型和是否定价的条件筛选掉不符合的项目
            if($priceType)
                if($val['priceType']!=$priceType)
                    continue;
            if($isPriced == 1)
                if(!$price)
                    continue;
            if($isPriced == 0)
                if($price)
                    continue;

            //通过筛选的频道获取消费者人次和课程数量
            $val['cusNumber'] = $this->getCustomerNumber($val['id']);
            $val['courseNumber'] = $schoolModel->getCourseNumber($val['id']);

            //跟据数字类型的priceType给类型命名
            $val['type'] = $this->getPriceTypeName($price['priceType']);
            $val['public'] = $val['is_public']?"公开":"私有";
            array_push($resultArr,$val);
        }
        return $resultArr;

    }

    /*
     * 获取某指定频道下课程的定价完整信息
     */
    function getSchoolCoursePrice($schoolId){
        $tblResource = new DB_Sso_Resource();

        $resultArr = [];
        //get school Course
        $courseType = Common_Config::PUBLIC_COURSE_TYPE;
        $courseList = $tblResource->fetchAll("id,name,price_type,cur_price","where entrance_id = {$schoolId} and enabled = 1 and type = {$courseType}");
        //loop get prices
        $index = 0;
        foreach($courseList as $k=>$value){
            //push courseInfo
            array_push($resultArr,array("course"=>$value['name'],"courseId"=>$value['id'],"type"=>$this->getPriceTypeName($value['price_type']),
                "price_type"=>$value['price_type'],"price"=>$value['cur_price'],"chapter"=>"","chapterId"=>0,"section"=>"","sectionId"=>0,
                "cusNumber"=>$this->getCustomerNumber($schoolId,$value['id']),"parentId"=>0,"No"=>$index,"resourceType"=>Common_Config::PUBLIC_COURSE_TYPE));
            $index++;
            $chapter = $tblResource->fetchAll("id,name,price_type,cur_price","where entrance_id = {$schoolId} and enabled = 1 and parent_id = {$value['id']}");
            foreach($chapter as $v=>$val){
                //push chapter info
                array_push($resultArr,array("course"=>$value['name'],"courseId"=>$value['id'],"type"=>$this->getPriceTypeName($val['price_type']),
                    "price_type"=>$val['price_type'],"price"=>$val['cur_price'],"chapter"=>$val['name'],"chapterId"=>$val['id'],"section"=>"","sectionId"=>0,
                    "cusNumber"=>'-',"parentId"=>$value['id'],"No"=>$index,"resourceType"=>Common_Config::PUBLIC_CHAPTER_TYPE));
                /*array_push($resultArr,array("course"=>"","courseId"=>$value['id'],"type"=>$this->getPriceTypeName($val['price_type']),
                    "price_type"=>$val['price_type'],"price"=>$val['cur_price'],"chapter"=>$val['name'],"chapterId"=>$val['id'],"section"=>"","sectionId"=>0,
                    "cusNumber"=>'-',"parentId"=>$value['id'],"No"=>$index,"resourceType"=>Common_Config::PUBLIC_CHAPTER_TYPE));*/
                $index++;
                $section = $tblResource->fetchAll("id,name,price_type,cur_price","where entrance_id = {$schoolId} and enabled = 1 and parent_id = {$val['id']}");
                foreach($section as $m=>$vm){
                    //push section info
                    array_push($resultArr,array("course"=>$value['name'],"courseId"=>$value['id'],"type"=>$this->getPriceTypeName($vm['price_type']),
                        "price_type"=>$vm['price_type'],"price"=>$vm['cur_price'],"chapter"=>$val['name'],"chapterId"=>$val['id'],"section"=>$vm['name'],"sectionId"=>$vm['id'],
                        "cusNumber"=>'-',"parentId"=>$val['id'],"No"=>$index,"resourceType"=>Common_Config::PUBLIC_SECTION_TYPE));
                    /*array_push($resultArr,array("course"=>"","courseId"=>$value['id'],"type"=>$this->getPriceTypeName($vm['price_type']),
                        "price_type"=>$vm['price_type'],"price"=>$vm['cur_price'],"chapter"=>"","chapterId"=>$val['id'],"section"=>$vm['name'],"sectionId"=>$vm['id'],
                        "cusNumber"=>'-',"parentId"=>$val['id'],"No"=>$index,"resourceType"=>Common_Config::PUBLIC_SECTION_TYPE));*/
                    $index++;
                }
            }
        }

        return $resultArr;
    }

    /*
     * 获取指定频道的完整定价信息
     */
    function  getSingleSchoolPrice($schoolId){
        $schoolPrice = $this->getSchoolPrice(0,2,2,'',$schoolId);
        $resultArr = array("school"=>$schoolPrice[0]['customer_name'].'-'.$schoolPrice[0]['customer_title'],"price"=>$schoolPrice[0]['price'],
            "priceType"=>$schoolPrice[0]['priceType'],'type'=>$schoolPrice[0]['type'],"cusNumber"=>$schoolPrice[0]['cusNumber'],"courseNumber"=>$schoolPrice[0]['courseNumber'],);

        return $resultArr;
    }


    /*
     * 获取指定频道下的消费者人次
     */
    function getCustomerNumber($schoolId,$courseId =0){
        $tblUserBought = new DB_Udo_UserBought();

        $where = "where schoolId = {$schoolId}";
        $resourceCourse = Common_Config::UDO_RESOURCE_COURSE;
        if($courseId)
            $where.= " and resourceId = {$courseId} and resourceType = {$resourceCourse}";
        $queryCount = $tblUserBought->queryCount($where);
        return $queryCount;
    }

    /*
     * 获取频道定价类型的命名信息
     */
    function getPriceTypeName($priceType){
        switch($priceType){
            case Common_Config::UDO_PRICETYPE_COIN:
                $type = "U币";
                break;
            case Common_Config::UDO_PRICETYPE_CREDIT:
                $type = "学分";
                break;
            case Common_Config::UDO_PRICETYPE_FREE:
                $type = "免费";
                break;
            default:
                $type = "未标价";
                break;
        }
        return $type;
    }

    /*
     * 更新测试数据——给资源定价
     */
    function price(){
        $tblEntrance = new DB_Sso_Entrance();
        $tblSchoolPrice = new DB_Udo_SchoolPrice();
        $tblResource = new DB_Sso_Resource();

        $schoolPrice = $tblSchoolPrice->fetchAll("resourceId,price");
        $priceCredit = 20;
        $priceCoin = 10;
        foreach($schoolPrice as $k=>$value){
            $ids = $tblResource->fetchAll("id","where entrance_id ={$value['resourceId']} and type = 6");
            //print_r($ids);
            switch($value['resourceId']%3){
                case 0:
                    //$tblSchoolPrice->insert(array("resourceId"=>$value['id'],"priceType"=>1,"price"=>$priceCredit,"createTime"=>time()));
                    //$priceCredit+=10;
                    foreach($ids as $l=>$val){
                        $tblResource->update($val['id'],array("price_type"=>1,"cur_price"=>floor($value['price']/0.8/count($ids))));
                        //第一章免费
                        $chapterIds = $tblResource->fetchAll("id","where entrance_id ={$value['resourceId']} and parent_id = {$val['id']} and type = 7","order by id asc");
                        $sectionIds = $tblResource->fetchAll("id","where entrance_id ={$value['resourceId']} and parent_id = {$chapterIds[0]['id']} and type = 8");
                        //如果该课程下面只有一个章，那么章节不免费，价格和课程一致，否则该章下面的节全部免费
                        if(count($chapterIds) == 1){
                            foreach($sectionIds as $m=>$s)
                                $tblResource->update($s['id'],array("price_type"=>1,"cur_price"=>floor($value['price']/0.8/count($ids))));
                            $tblResource->update($chapterIds[0]['id'],array("price_type"=>1,"cur_price"=>floor($value['price']/0.8/count($ids))));
                        }
                        else{
                            foreach($sectionIds as $m=>$s)
                                $tblResource->update($s['id'],array("price_type"=>3,"cur_price"=>0));
                            $tblResource->update($chapterIds[0]['id'],array("price_type"=>3,"cur_price"=>0));
                        }
                    }
                    break;
                case 1:
                    foreach($ids as $l=>$val){
                        $tblResource->update($val['id'],array("price_type"=>2,"cur_price"=>floor($value['price']/0.8/count($ids))));
                        //第一章免费
                        $chapterIds = $tblResource->fetchAll("id","where entrance_id ={$value['resourceId']} and parent_id = {$val['id']} and type = 7","order by id asc");
                        $sectionIds = $tblResource->fetchAll("id","where entrance_id ={$value['resourceId']} and parent_id = {$chapterIds[0]['id']} and type = 8");
                        //如果该课程下面只有一个章，那么章节不免费，价格和课程一致，否则该章下面的节全部免费
                        if(count($chapterIds) == 1){
                            foreach($sectionIds as $m=>$s)
                                $tblResource->update($s['id'],array("price_type"=>2,"cur_price"=>floor($value['price']/0.8/count($ids))));
                            $tblResource->update($chapterIds[0]['id'],array("price_type"=>2,"cur_price"=>floor($value['price']/0.8/count($ids))));
                        }
                        else{
                            foreach($sectionIds as $m=>$s)
                                $tblResource->update($s['id'],array("price_type"=>3,"cur_price"=>0));
                            $tblResource->update($chapterIds[0]['id'],array("price_type"=>3,"cur_price"=>0));
                        }
                    }
                    break;
                case 2:
                    foreach($ids as $l=>$val){
                        $tblResource->update($val['id'],array("price_type"=>3,"cur_price"=>0));
                    }
                    break;
            }
        }
    }

    /*
     * 获取学分规则
     */
    function getCreditAction(){
        $tblCreditAction = new DB_Udo_CreditAction();
        $creditAction = $tblCreditAction->fetchAll("*","where isValid = 1");
        foreach ($creditAction as $k=>$val){
            $creditAction[$k] = array_merge($val,$this->getActionProp($val['outputInput'],$val['frequency']));
        }
        return $creditAction;
    }

    /*
     * 测试数据——用户购买信息
     * 所有用户均购买了id模3为0的频道
     * 所有用户购买了所有频道的第一个课程
     */
    function testBought(){
        $tblUser = new DB_Sso_User();
        $tblBought = new DB_Udo_UserBought();
        $tblEntrance = new DB_Sso_Entrance();
        $tblResource = new DB_Sso_Resource();
        $entranceIds = $tblEntrance->fetchAll("id","where id%3=0");
        $entranceIdsPart = $tblEntrance->fetchAll("id","where id%3=1 or id%3=2");

        $userIds = $tblUser->fetchAll("id");
        //$entranceId = $tblEntrance->scalar("id");

        foreach($userIds as $k=>$value){
            //首先对所有模3为0的，全部购买
            foreach($entranceIds as $m=>$val){
                $resourceIds = $tblResource->fetchAll("id","where entrance_id = {$val['id']} and type = 6");
                $insert = $tblBought->insert(array("userId"=>$value['id'],"resourceId"=>$val['id'],"resourceType"=>1,
                    "schoolId"=>$val['id'],"createTime"=>time()));
                foreach($resourceIds as $n=>$v)
                    $tblBought->insert(array("userId"=>$value['id'],"resourceId"=>$v['id'],"resourceType"=>2,
                        "schoolId"=>$val['id'],"createTime"=>time()));
            }
            foreach($entranceIdsPart as $l=>$v2){
                $resourceId = $tblResource->scalar("id","where entrance_id = {$v2['id']} and type = 6");
                $tblBought->insert(array("userId"=>$value['id'],"resourceId"=>$resourceId['id'],"resourceType"=>2,
                    "schoolId"=>$v2['id'],"createTime"=>time()));
            }
        }
    }
    /*
     * 用户账户信息
     */
    function testAccount(){
        $tblAccount = new DB_Pay_Account();
        $tblUser = new DB_Sso_User();
        $userIds = $tblUser->fetchAll("id,name");
        foreach($userIds as $k=>$value){
            $tblAccount->insert(array("sso_id"=>$value['id'],"user_name"=>$value['name'],"score"=>500,"amt"=>50,"created_time"=>date('Y-m-d H:i:s')));
        }
    }


    /*
     * 获取学分操作的频次和产消名称
     */
    function getActionProp($io,$freq){
        switch($io){
            case 1:
                $io = "产出";
                break;
            case 2:
                $io = "消耗";
                break;
            default:
                $io = "未定义";
                break;
        }

        switch($freq){
            case 1:
                $freq = "每日";
                break;
            case 0:
                $freq = "首次";
                break;
            default:
                $freq = "未定义";
                break;
        }

        return array("io"=>$io,"freq"=>$freq);
    }

    /*
     * 获取订单的状态集合
     */
    function getOrderPaytype(){
        $type = [];
        array_push($type,array("type"=>Common_Config::UDO_PAYTYPE_COIN,"name"=> "U币购买"));
        array_push($type,array("type"=>Common_Config::UDO_PAYTYPE_CREDIT,"name"=> "学分购买"));
        array_push($type,array("type"=>Common_Config::UDO_PAYTYPE_RECHARGE,"name"=> "U币充值"));
        return $type;
    }
    /*
     * 获取订单的类型集合
     */
    function getOrderStatus(){
        $status = [];
        array_push($status,array("type"=>Common_Config::ORDER_SUCCESS,"name"=> "支付成功"));
        array_push($status,array("type"=>Common_Config::ORDER_NOT_PAY,"name"=> "尚未支付"));
        array_push($status,array("type"=>Common_Config::ORDER_FAIL,"name"=> "订单支付失败"));
        array_push($status,array("type"=>Common_Config::ORDER_CLOSED,"name"=> "订单关闭"));
        return $status;
    }
}
<?php

class MessageController extends Base_Contr
{
    public $needLogin = true;

    /*
     * 该控制器被系统定时调用
     */
    //检查频道更新并通知相应用户
    function newSchoolMessageAction()
    {

        //从SSO获取频道列表,暂时注释掉从SSO取频道的过程
        $messageModel = new MessageModel();
        $result = $messageModel->getSchool();

        //如果SSO没有找到该频道
        if ($result == -1)
            $this -> displayJsonUdo(Common_Error::ERROR_SSO_ERROR_SCHOOL);

        if ($result == -3)
            $this-> displayJsonUdo(Common_Error::ERROR_FIRST_UPDATE);

        //如果没有新频道
        if ($result == -2)
            $this->displayJsonUdo(Common_Error::ERROR_SCHOOL_NOT_EXISTS);

        //如果有新频道，需要获取所有注册用户，并比对是否适合推送给该用户
        /*$userModel = new UserModel();
        $user = $userModel->getAllUser();*/

        //如果用户返回有误
       /* if ($user == -1)
            $this->displayJsonUdo(Common_Error::ERROR_SSO_ERROR_USER);*/

        //print_r($result);
        //以下result和user是测试数据
/*        $result = array(
            0=> array("id"=>1,"city_id"=>3,"name"=>"双十","title"=>"双十中学","grade"=>11,"apiUdoUrl"=>"http://112.126.68.99:8520") ,
            1=> array("id"=>2,"city_id"=>4,"name"=>"华英","title"=>"华英学校","grade"=>14,"apiUdoUrl"=>"http://112.126.68.99:8520"),
            2=> array("id"=>3,"city_id"=>4,"name"=>"三明","title"=>"三明中学","grade"=>21,"apiUdoUrl"=>"http://112.126.68.99:8520"));*/

        //对于每一个新频道，过滤出该频道适合推荐给的用户组
        $new_array = [];
        $userModel = new UserModel();
        foreach ($result as $k=>$val){
            $temp_array = [];
            //获取到该频道下有权限访问的用户列表
            $user = $userModel->getAllUser( $val['id'] );
            if($user <=0 )
                continue;
            foreach ($user as $l=>$ids){
                //对于用户群中的某一个id，获取该用户的详细信息
                $value = $userModel->getUserInfo($ids);
                //print_r($value);
                /**
                 * 检查用户条件，当用户满足以下条件时，才将该用户加入推荐列表
                 * 如果频道有年级限制，且用户的年级条件符合，将用户加入
                 */
                $grade_filter = 1;
                if ($val['grade']){
                    switch($value['grade']){
                        case strstr($value['grade'],'小'):
                            if ($val['grade'] >= 11 && $val['grade']!=14)
                                $grade_filter = 1;
                            else
                                $grade_filter = 0;
                            break;
                        case strstr($value['grade'],'初'):
                            if (($val['grade'] >= 21 && $val['grade']!=24) || $val['grade']<=10)
                                $grade_filter = 1;
                            else
                                $grade_filter = 0;
                            break;
                        case strstr($value['grade'],'高'):
                            if ( $val['grade']<=20)
                                $grade_filter = 1;
                            else
                                $grade_filter = 0;
                            break;
                        default:
                            $grade_filter = 1;
                            break;
                    }
                }

                if ( ($value ['provinceId'] && $value ['grade'] && $val['cityId'] && ($val['cityId'] == $value['provinceId'] || $val['cityId'] == $value['cityId'] || $val['cityId'] == $value['areaId'])
                && $grade_filter)
                ||($value['provinceId'] && !$value['grade'] && $val['cityId'] && ($val['cityId'] == $value['provinceId'] || $val['cityId'] == $value['cityId'] || $val['cityId'] == $value['areaId']))
                ||(!$value['provinceId'] && $value['grade'] && $grade_filter)
                ||(!$value['provinceId'] && !$value['grade'])
                    ||(!$val['cityId'])
                ){
                    $new_array[$k][0] = $val['id'];
                    $new_array[$k][1] = $val['customerName'];
                    $new_array[$k][2] = $val['apiUdoUrl'];
                    if(!$temp_array)
                        $temp_array = array($value['id']);
                    else{
                        array_push($temp_array,$value['id'] );
                    }
                    }
            }
            $new_array[$k][3] = $temp_array;
        }

        //print_r($new_array);


        //新频道可以推荐的用户数组完成，将参数传给消息推送接口
        $title ="UDO微课堂上新频道啦~~~";
        $type = 1;
        $messageType = Common_Config::UDO_SCHOOL_MESSAGE_TYPE;
        $messageModel = new MessageModel();

        //对于每一个频道，发送频道上线消息。有多少频道，发送多少组消息
        foreach ($new_array as $k=>$val){
            $retry = 0;
            $result = -1;
            $receiveUserId = $val[3];
            $text = "小主，".$val[1]."新频道上线啦！点击进入>>";
            //print_r($receiveUserId);
            //print_r($text);
            $custom_data_raw = json_encode(array("school_id"=>$val[0],"course_id"=>"","url"=>$val[2]));
            $mid = $messageModel ->messageLog($type,$messageType,$custom_data_raw,0,$receiveUserId,null,$title,$text);
            $custom_data = json_encode(array("id"=>$mid,"school_id"=>$val[0],"course_id"=>"","url"=>$val[2],"prof_type"=>100,"title"=>$title,"text"=>$text));
            //print_r($custom_data);

            //如果发送失败，再尝试三次
            while($retry <=3 and $result == -1){
                $result = $messageModel -> sendMessage($type,$custom_data,$receiveUserId,$custom_data,$title,$text);
                //print_r($result);
                $retry++;
            }

            //此处确实发送失败的处理

        }
    }

    /*
     * 客户端获取首页消息
     */
    function getMessageAction(){
        $frontMessage = new MessageModel();
        $result = $frontMessage->getFrontMessage();

        if($result == -1)
            $this->displayJsonUdo(Common_Error::ERROR_MESSAGE_NOT_EXISTS);

        /*if($result['type'] == Common_Config::UDO_FRONT_ITEM_MESSAGE){*/
/*            $return_array = array("id"=>$result['id'],"type"=>$result['type'],"action"=>0,"content"=>$result['content'],"title"=>$result['title'],
                "schoolUrl"=>$result['school_url'],"schoolId"=>$result['school_id']);*/


        $this->displayJsonUdo(Common_Error::ERROR_SUCCESS,$result);
        /*}*/

    }

    /*
     * 客户端写入action
     */

    function messageActionAction(){
        $request = $this->getRequest();
        if('POST' == $request->getMethod())
        {
            $action = $this->post()->get("action");
            $id = $this->post()->get("id");
        }

        $action = 1;
        $id = 1;
        $messageModel = new messageModel();
        $result = $messageModel->frontMessageAction($action,$id);

        if($result)
            $this->displayJsonUdo(Common_Error::ERROR_SUCCESS);
        else
            $this->displayJsonUdo(Common_Error::ERROR_FAIL);
    }

    /*
     * 给小学用户推送开心作文上线消息
     */
    function kaixinMessageAction(){
        $title ="作文拿不了高分？作文怎么写才不low？";
        $type = 1;
        $messageType = Common_Config::UDO_SCHOOL_MESSAGE_TYPE;
        $retry = 0;
        $result = -1;
        $messageModel = new MessageModel();
        $receiveUserId = $messageModel->kaixinUser();
        $text = "一个月作文技巧手到擒来，找回孩子“被忽视的情绪和思维”！";
        //print_r($receiveUserId);
        //print_r($text);
        $custom_data_raw = json_encode(array("school_id"=>2769,"course_id"=>"","url"=>"http://123.57.182.19:8520/"));
        $mid = $messageModel ->messageLog($type,$messageType,$custom_data_raw,0,$receiveUserId,null,$title,$text);
        $custom_data = json_encode(array("id"=>$mid,"school_id"=>2769,"course_id"=>"","url"=>"http://123.57.182.19:8520/","prof_type"=>100,"title"=>$title,"text"=>$text));
        //print_r($custom_data);

        //如果发送失败，再尝试三次
        while($retry <=3 and $result == -1){
            $result = $messageModel -> sendMessage($type,$custom_data,$receiveUserId,$custom_data,$title,$text);
            //print_r($result);
            $retry++;
        }
    }

}
?>
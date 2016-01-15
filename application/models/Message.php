<?php
/**
 * 消息业务模型
 * @author lijiannan@howdo.cc
 */



class MessageModel
{

    public $school_id  ;


    /**
     * 从公共云平台获取新频道列表
     */

    public function getSchoolId(){
        $tbl = new DB_Udo_School();
        $result = $tbl -> scalar("id,schoolId");
        return $result;
    }

    public function updateSchoolId($id,$schoolId){
        $tbl = new DB_Udo_School();
        $insert_array = array("schoolId" => $schoolId);
        $result = $tbl -> update($id,$insert_array);
        return $result;
    }

    public function getSchool()
    {


        $url = Common_Config::SSO_ENTRY_URL;

        $array = [];
        $index = 0;
        $rows = 20;
        $cl = new Common_Curl();
        for ($page = 1; $index == 0 || ($index>0 && array_key_exists($index,$array)) ;$page++){
            $post_data = array("page"=>$page,"rows"=>$rows);
            //print_r($post_data);

            $array = array_merge($array,$cl->request($url, $post_data));
            //print_r($entry);
            $index = $page * $rows - 1;
        }

        //判断是否返回失败信息
        if (empty($array))
            return -1;

        $new_array = [];
        $index = 0;
        //print_r($new_array);
        $messageModel = new MessageModel();
        $schoolIds = $messageModel->getSchoolId();
        $schoolId = $schoolIds['schoolId'];

        foreach($array as $k=>$val) {
            if (is_array($val) && $val['id'] > $schoolId ){
                $new_array[$index] = $val;
                $index++;
                $messageModel->updateSchoolId($schoolIds['id'],$val['id']) ;
            }
        }

        //首次启动，不进行提示
        $update_time = $messageModel->getSchoolTime();
        $messageModel->updateGetSchoolTime();
        if(!$update_time){
            return -3;
        }

        //print_r($new_array);
        //没有新更新的频道
        if ($new_array == [])
            return -2;

        return $new_array;
    }

    /*
     * 查询获取新学校接口的查询次数
     */
    public function getSchoolTime(){
        $tbl = new DB_Udo_School();
        $result = $tbl->scalar("id,schoolId,time,last_time");
        return $result['time'];
    }

    /*
     * 更新查询新学校的次数和时间
     */

    public function updateGetSchoolTime(){
        $tbl = new DB_Udo_School();
        $result = $tbl->scalar("id,schoolId,time,last_time");
        $update_array = array("time"=>$result['time']+1,"last_time"=>time());
        $tbl -> update($result['id'],$update_array);
    }
    /*
     * 和SSO交互，将要发送的消息内容发送给sso
     */
    public function sendMessage($type,$custom_data,$receiveUserId,$transmissionContent,$title,$text){
        $url = Common_Config::SSO_TRANS_URL;
        $accessKeyId = Common_Config::UDO_OP_DOMAINID;
        $sendUserId = 0;

        $receiveUserIds ="";
        foreach ($receiveUserId as $k => $val){
            if(array_key_exists($k+1,$receiveUserId))
                $receiveUserIds.= $val.",";
            else
                $receiveUserIds.= $val;
        }

        //print_r($receiveUserIds);
        $post_data = array("type"=>$type,"customData"=>$custom_data,"accessKeyId"=>$accessKeyId,
            "sendUserId"=>$sendUserId,"receiveUserId"=>$receiveUserIds,"transmissionContent"=>$transmissionContent,
            "title"=>$title,"text"=>$text);

        $cl = new Common_Curl();
        $array = $cl->request($url, $post_data);
        //print_r($array);

        //print_r($post_data);
        //判断是否返回失败信息
        if (is_array($array)&& array_key_exists('code',$array) && $array['code'] == 0)
            return -1;
        return $array;
    }

    /*
     * 发送消息后，记录消息日志
     */
    public function messageLog($type,$message_type,$custom_data,$sendUserId,$receiveUserId,$transmissionContent,$title,$text){
        $tbl = new DB_Udo_Message();
/*        foreach($receiveUserId as $k=>$val){
            $insert_array = array("type"=>$type,"message_type"=>$message_type,"custom_data"=>$custom_data,
                "send_user_id"=>$sendUserId,"receive_user_id"=>$val,"transmission_content"=>$transmissionContent,
                "title"=>$title,"text"=>$text);
            $result = $tbl ->insert($insert_array);
        }*/

        $insert_array = array("type"=>$type,"message_type"=>$message_type,"custom_data"=>$custom_data,
            "send_user_id"=>$sendUserId,"receive_user_id"=>implode(",",$receiveUserId),"transmission_content"=>$transmissionContent,
            "title"=>$title,"text"=>$text);
        $result = $tbl ->insert($insert_array);
        return $result;
    }

    /*
     * 获取首页消息
     */

    public function getFrontMessage(){
        //获取当前用户登录信息
        $user = new UserModel();
        $uid = $user->getUid();
        $time =time();

        $tbl = new DB_Udo_FrontMessage();
        $result = $tbl->fetchAll("id,content,title,type,action,schoolId,schoolUrl","where uid = {$uid} and expire_time >= {$time}");

        if (!$result)
            return -1;
        //更新用户的行为信息
        foreach ($result as $k=>$val){
            if (!$val['action'])
                $tbl ->update($val['id'],array("action"=>1));
            if($val['type']==0)
                $result [$k]['action'] = 0;
            else
                $result [$k]['action'] = 1;
        }

        return $result;
    }

    public function frontMessageAction($action,$id){
        //获取当前用户登录信息
        $user = new UserModel();
        $uid = $user->getUid();

        switch ($action){
            case 0:
                $real_action = 2;
                break;
            case 1:
                $real_action = 3;
                break;
            default:
                $real_action = $action;
                break;
        }

        $tbl = new DB_Udo_FrontMessage();
        $result = $tbl->update($id,array("action"=>$real_action));
        return $result;
        
    }

    function kaixinUser(){
        $tblSchoolSta = new DB_Udo_SchoolStatistics();
        $user = $tblSchoolSta->fetchAll("userId","where schoolId = 10 or schoolId = 2746 group by userId");
        $userIds = $tblSchoolSta->columnRow($user,"userId");
        return $userIds;
    }
}
    ?>
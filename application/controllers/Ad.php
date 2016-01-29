<?php
class AdController extends Base_Contr
{
    public $needLogin = true;

    /**
     * 用户是否存在
     */
    public function getAdAction()
    {
        //广告接口首先拿客户端传来的ssotoken 在SSO进行验证获取用户信息
        $request = $this->getRequest();
        $userModel = new UserModel();
        $userModel->logout();

        if('POST' == $request->getMethod()) {
            $token = trim($this->post()->get("ssotoken"));
        }
        else
            $token = $this->get("ssotoken");

        //$token = "1";
        if(!$token)
            $this->displayJsonUdo(Common_Error::ERROR_PARAM);

        //获取到用户信息
        $url = Common_Config::SSO_SCHOOL_URL;
        $post_data = array("ssotoken"=>$token);
        //print_r($post_data);
        $cl = new Common_Curl();
        $result = $cl->request($url, $post_data);

/*                $result = array("id"=>8 , "grade"=>11,"province"=>3,"city"=>4,"area"=>5,
                    "entrances"=>array(0=>array("id"=>1,"name"=>"双十","title"=>"双十网校","grade"=>11,"baseUrl"=>"1",
        "udoUrl"=>"2","city_id"=>4),
        1=>array("id"=>2,"name"=>"华英","title"=>"华英网校","grade"=>14,"baseUrl"=>"1",
            "udoUrl"=>"2","city_id"=>6),
        2=>array("id"=>3,"name"=>"三明","title"=>"三明网校","grade"=>15,"baseUrl"=>"1",
            "udoUrl"=>"2","city_id"=>4)));*/
        //print_r($result);

        if(array_key_exists('code', $result) && $result['code'] == 0)
            $this->displayJsonUdo(Common_Error::ERROR_FAIL,null,"SSO没有返回正确的用户信息哦~");

        //$result = array ("id"=> 2779,"province"=>3,"grade"=>11);
        //$result = array ("id"=> 2779,"province"=> 0,"grade"=>11);
        //sso返回成功，开始进行广告过滤

        //print_r($result);
        //过滤出和用户所在地区和年级一致的广告
        $ad = new AdModel();
        $adlist = $ad -> getAd($result);

        $uid = $userModel->getUserId($token);
        /*$kaixin = $ad->filterPrimary($uid);
        if(is_array($kaixin))
            $adlist = $kaixin;*/
        //计算总竞价值
        $price_sum = 0;
        $temp_arr = array();
        foreach ($adlist as $i=>$val){
            $price_sum +=$val['price'];
        }

        //生成随机数
        $arr=array();
        $arr[]=rand(1,100);
        $arr=array_unique($arr);
        $rand = implode(" ",$arr);

        //获得随机url
        $url = [];
        $rate = 0;

        foreach ($adlist as $i=>$val){
            $temp_arr[$i] = $val['price']/$price_sum*100 + ($i-1>=0 ? $temp_arr[$i-1] : 0);
            //print_r($i."   ".$temp_arr[$i]);
            if ($rand <=$temp_arr[$i] && $rand >=($i-1>=0 ? $temp_arr[$i-1] : 0)) {
                $url = $val;
                $rate = $val['price']/$price_sum;
            }
        }

        //将推送的广告写入广告推送表
        $ad->adLog($url['id'],0,$rate);
        $ad->adPushSum($url['id']);
        $ad->adStatistics($url['id']);

        $this->displayJsonUdo(Common_Error::ERROR_SUCCESS , $url);

    }
}
?>
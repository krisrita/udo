<?php
/**
 * 广告业务模型
 * @author lijiannan@howdo.cc
 */
class AdModel
{
    /*
     * Array
(
    [id] => 11
    [mobile] => 18202565705
    [clientId] => 1f320555501d2120a24aefbddcd14f3b
    [name] => 服务器5
    [gender] => 1
    [email] => 18202565705@qq.com
    [qq] =>
    [province] => 0
    [city] => 0
    [area] => 0
    [title] =>
    [intro] =>
    [thumb] =>
    [school] =>
    [score] => 0
    [amt] => 0
    [freezeAmt] => 0
    [withdrawAmt] => 0
    [withdrawFreezeAmt] => 0
    [entrances] => Array
        (
            [0] => Array
                (
                    [id] => 2703
                    [name] => UDO2.1
                    [customerName] => UDO2.1
                    [logo] =>
                    [apiBaseUrl] => 182.92.115.116:8521
                    [apiUdoUrl] =>
                )

        )

    [mobiles] =>
    [token] => token9ac1734e-cd08-45b3-aade-b6439ad2f960kHdezGH4
)
     * */
    public function getAd($array){
        $tbl = new DB_Udo_Ad();
        $list =[];
/*        $result = $tbl->fetchAll();
        print_r($result);*/

        $AdModel = new AdModel();

        //如果用户的地区和年级信息均有效
        if($array['province'] && $array['grade'])
            $list = $tbl -> fetchAll("id,price,url","where region_id={$array['province']} and ".$AdModel->getGradeClause($array['grade'])." and flag = 1","order by price asc");

        if ($array['province'] && !$array['grade'])
            $list = $tbl -> fetchAll("id,price,url","where region_id={$array['province']}  and flag = 1","order by price asc");

        if (!$array['province'] && $array['grade'])
            $list = $tbl -> fetchAll("id,price,url","where ".$AdModel->getGradeClause($array['grade'])." and flag = 1","order by price asc");

        if (!$array['province'] && !$array['grade'])
            $list = $tbl -> fetchAll("id,price,url","where flag = 1","order by price asc");

        //如果没有筛选出符合用户条件的广告，那么将所有广告作为推送对象
        if(!$list)
            $list = $tbl -> fetchAll("id,price,url","where flag = 1","order by price asc");

        return $list;
    }

    /*
     * 根据年级生成where语句
     */
    public function getGradeClause($grade){
        $where ="";
        switch($grade){
            case 1:
            case 2:
            case 3:
                $where = "crowd <11";
                break;
            case 4:
            case 5:
            case 6:
                $where = "(crowd < 12 or crowd ==14)";
                break;
            case 11:
            case 12:
                $where = "( crowd >10 and crowd <21)";
                break;
            case 13:
                $where = "((crowd >10 and crowd <22) or crowd ==24)";
                break;
            case 21:
            case 22:
            case 23:
                $where = "crowd >20";
                break;}
        return $where;
    }


/*
 * 插入广告推送日志
 */

    public function adLog($ad_id,$score=0,$rate){
        $tbl = new DB_Udo_AdLog();
        $user = new UserModel();
        $uid = $user -> getUid();

        $insert_array = array("uid" => $uid , "ad_id" => $ad_id , "score" => $score , "rate" => $rate , "push_time" => time() );
        $tbl ->insert($insert_array);
    }
/*
 * 更新单条广告推送次数
 */
    public function adPushSum ($ad_id){
        $tbl = new DB_Udo_AdPush();
        $user = new UserModel();
        $uid = $user -> getUid();

        $id = $tbl -> scalar("id,push_time","where ad_id = {$ad_id}");

        $update_array = array("ad_id" => $ad_id , "push_time" => ($id['id']?$id['push_time']+1:1) , "last_user" => $uid , "last_time" => time());
        if ( !$id['id'] )
            $tbl -> insert($update_array);
        $tbl -> update($id['id'],$update_array);

    }

    /*
     *更新单用户某条广告推送次数
     */
    public function adStatistics($ad_id){
        $tbl = new DB_Udo_AdStatistics();
        $user = new UserModel();
        $uid = $user -> getUid();

        $array = $tbl -> scalar("id,push_number","where uid = {$uid} and ad_id = $ad_id ");
        $update_array = array("uid" => $uid , "ad_id" => $ad_id , "last_time" => time() , "push_number" => ($array['id']?$array['push_number']+1:1));
        if( !$array['id'] )
            $tbl -> insert( $update_array);

        $tbl -> update($array['id'] ,$update_array);
    }

    /*
     * 小学广告推送
     */
    public function filterPrimary($uid){
        $tblSchoolSta = new DB_Udo_SchoolStatistics();
        $tblAd = new DB_Udo_Ad();
        $adList = [];
        $primary = $tblSchoolSta->query("select schoolId,userId from udo_school_statistics where (schoolId = 12 or schoolId = 2746) and userId = {$uid}");

        if($primary){
            $ad = $tblAd->scalar("id,price,url","where description = 'kaixinzuowen'");
            array_push($adList,$ad);
            return $adList;
        }
        else
            return -1;


    }
}
?>
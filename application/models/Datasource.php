<?php

class DatasourceModel
{

    /**
     * 省市区三级结构
     */
    function getRegion()
    {
        $tblRegion = new DB_Howdo_Region();
        $list = $tblRegion->fetchAll("region_id,pid,region_name", "", "order by pid asc, region_id asc");
        $data = array();

        foreach ($list as $row) {
            $data[$row['pid']][$row['region_id']] = $row['region_name'];
        }

        return $data;
    }


    function getCity($id)
    {
        $tblRegion = new DB_Howdo_Region();
        return $tblRegion->fetchRow($id, "region_id as city_id, region_name as city_name");
    }
}
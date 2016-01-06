<?php
/**
 * Created by PhpStorm.
 * User: Kris
 * Date: 2015/12/30
 * Time: 11:51
 */
/*
 * 公共处理函数模型
 */
class PublicModel{

    /*
     * 后台页码范围计算：
     * 规则：页码范围显示10个，当前页总在前6个
     * 参数：page:当前页;pageNumber：总页数
     */
    function pageCal($page,$pageNumber){
        if($pageNumber <= 10 )
            $pagination =  range(1,$pageNumber);
        elseif($page<=6&&$pageNumber > 10){
            $pagination =  range(1,10);
        }
        else{
            $pagination = range($page-5,$page+4);
        }

        return $pagination;
    }
}
<?php

class Common_Time
{
    /** 
     * 流格式
     * @param      int $time
     * @access     public
     * @return     string
     * @update     2014/3/17
    */  
    public static function flow($time, $format = 'Y-m-d') 
    { 
        $time = intval($time);
        $t = time();
        $yeartime = strtotime(date('Y-01-01'));
        $beforeyestodaytime = strtotime(date("Y-m-d", time() - 24*60*60));
        $daytime = $t - 24*60*60;
        $hourtime = $t - 60*60;
        $minutetime = $t - 60; 

        
        if ($time < $yeartime) {
            return date("y-m-d H:i", $time);
        } elseif ( $time < $beforeyestodaytime ) { 
            return date("m-d H:i", $time );
        } elseif ( $time < $daytime ) { 
            return '昨天 ' . date("H:i", $time);
        } elseif ( $time < $hourtime ) { 
            return round( ( $t - $time )/3600, 0, PHP_ROUND_HALF_DOWN) . '小时前';
        } elseif ( $time < $minutetime ) { 
            return round( ( $t - $time )/60 , 0, PHP_ROUND_HALF_DOWN) . '分钟前';
        } else {
            return "刚刚";
        }
    }   
    /*
    public static function flow($time, $format = 'Y-m-d') 
    { 
        $time = intval($time);
        $t = time();
        $weektime = $t - 7*24*60*60;
        $daytime = $t - 24*60*60;
        $hourtime = $t - 60*60;
        $minutetime = $t - 60; 

        if ( $time < $weektime ) { 
            return date( $format , $time );
        } elseif ( $time < $daytime ) { 
            return intval( ( $t - $time ) / (24*60*60) ) . '天前';
        } elseif ( $time < $hourtime ) { 
            return intval( ( $t - $time ) / ( 60*60 )  ) . '小时前';
        } elseif ( $time < $minutetime ) { 
            return intval( ( $t - $time ) /  60  ) . '分钟前';
        } else {
            return $t - $time . "秒前";
        }
    }   
    */


    /**
     * 剩余时间
     */
    public static function remain($time, $format='Y-m-d')
    {
        $remain = $time - time();
        if ($remain < 0) {
            return "";
        }

        $remainFmt = "";
        
        if ($remain > 24*60*60 ) { 
            $remainFmt .= intval($remain/86400) . '天';
            $remain -= intval($remain/86400) * 86400;
        }
        
        if ( $remain > 60*60 ) { 
            $remainFmt .= intval($remain / 3600) . '小时';
            $remain -= intval($remain / 3600) * 3600;
        } 
        
        if ( $remain > 60 ) { 
            $remainFmt .= intval($remain / 60) . '分钟';
            $remain -= intval($remain / 60) * 60;
        }

        return $remainFmt .= $remain . "秒";
    }
}
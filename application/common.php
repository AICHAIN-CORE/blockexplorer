<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function getTimeDiff($time,$lang='cn'){
    $res_string = $time;
    if ($lang == 'cn'){
        $short = '刚刚';$ago = '前';$second = '秒';$minute = '分钟';$hour = '小时';$day = '天';
    }else{
        $short = 'a moment ago';$ago = 'ago';$second = 'secs ';$minute = 'min ';$hour = 'hour ';$day = 'day ';
    }
    if(!empty($time) && check_DT($time)){
        $time_diff = abs(intval(time() - strtotime($time)));
        if($time_diff < 1){
            $res_string = $short;
        }elseif ($time_diff < 60){//1分钟内
            $res_string = $time_diff . ' ' . $second . $ago;
        }elseif ($time_diff < 3600){//1小时内
            $min_part = floor($time_diff / 60);
            $min_part_s = $time_diff % 60;
            if(!$min_part_s){
                $res_string = $min_part . ' ' . $minute . $ago;
            }else{
                $res_string = $min_part . ' ' . $minute . ' ' . $min_part_s . ' ' . $second.$ago;
            }
        }elseif ($time_diff < 86400){//1天内
            $hour_part = floor($time_diff / 3600);//小时
            $min_part = floor(($time_diff % 3600) / 60);//剩余分钟
            if(!$min_part){
                $res_string = $hour_part . ' ' . $hour . $ago;
            }else{
                $res_string = $hour_part . ' ' . $hour. ' '  . $min_part . ' ' .$minute . $ago;
            }
        }elseif ($time_diff < 604800){//1周内
            $day_part = floor($time_diff / 86400);//天
            $hour_part = floor(($time_diff % 86400) / 3600);//剩余分钟
            if(!$hour_part){
                $res_string = $day_part . ' ' . $day . $ago;
            }else{
                $res_string = $day_part . ' ' . $day. ' '  . $hour_part . ' ' . $hour . $ago;
            }
        }else{//大于1周
            $res_string = sliceDT($time);
        }
    }
    return $res_string;
}

function check_DT($date_time,$format='Y-m-d H:i:s'){
    if(date($format, strtotime($date_time)) == $date_time){
        return true;
    }
    return false;
}

function sliceDT($date_time){
    $res_string = $date_time;
    if(check_DT($date_time)){
        $year = date('Y', strtotime($date_time));
        $month = date('m', strtotime($date_time));
        $day = date('d', strtotime($date_time));
        $hour = date('H', strtotime($date_time));
        $min = date('i', strtotime($date_time));
        $sec = date('s', strtotime($date_time));
        if(date('Y') == $year){
            if(date('Ymd') == $year.$month.$day){
                $res_string = $hour.':'.$min.':'.$sec;
            }else{
                $res_string = $month.'/'.$day.' '.$hour.':'.$min;
            }
        }else{
            $res_string = $year.'/'.$month.'/'.$day;
        }
    }
    return $res_string;
}

function object_array($array) {
    if(is_object($array)) {
        $array = (array)$array;
    } if(is_array($array)) {
        foreach($array as $key=>$value) {
            $array[$key] = object_array($value);
        }
    }
    return $array;
}



//封装ajax失败返回
function error($mes='fail',$status=1){
    $arr=[
        'code'=>$status,
        'message'=>'',
        'content'=>[
            'msg'=>$mes
        ],
    ];
    return $arr;
}

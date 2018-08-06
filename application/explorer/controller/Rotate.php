<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/10
 * Time: 15:33
 */

namespace app\explorer\Controller;
use think\Controller;
class Rotate extends Controller{
//转
    public function zp(){
//        $arr = array("10", "30", "8", "5", "5", "5", "3", "0.1", "38.9");
//        $prize_id = $this->getRand($arr); //根据概率获取奖项id
//        $data['prize_site'] = $prize_id - 1;
        $arr8 = array(
            array("id"=>0,"name"=>"安慰奖","percent"=>40),
            array("id"=>1,"name"=>"玩具车","percent"=>10),
            array("id"=>2,"name"=>"自行车","percent"=>6),
            array("id"=>3,"name"=>"电动车","percent"=>5),
            array("id"=>4,"name"=>"摩托","percent"=>4),
            array("id"=>5,"name"=>"拖拉机","percent"=>5),
            array("id"=>6,"name"=>"70","percent"=>0),
            array("id"=>7,"name"=>"奥迪","percent"=>30),
        );
//下标存储数组100个下表，0-7 按概率分配对应的数量
        $indexArr = array();
        for($i=0;$i<sizeof($arr8);$i++){
            for($j=0;$j<$arr8[$i]['percent'];$j++){
//index 追加到数组indexArr
                array_push($indexArr, $i);
            }
        }
//数组乱序
        shuffle($indexArr);
//从下标数组中随机取一个下标作为中奖下标，$rand_index 是$indexArr的随机元素的下标（0-99）
        $rand_index = array_rand($indexArr,1);
//获取中奖信息
        $data['prize_site'] = $indexArr[$rand_index];
        echo json_encode($data);
//       这里的是一个完整的从数据库获取内容然后抽取奖项
//        $prize_arr=M("rotate")->select();
//        foreach ($prize_arr as $k=>$v) {
//            $arr[$v['id']] = $v['chance'];
//        }
//        $prize_id = $this->getRand($arr); //根据概率获取奖项id
//        foreach($prize_arr as $k=>$v){ //获取前端奖项位置
//            if($v['id'] == $prize_id){
//                $prize_site = $k;
//                break;
//            }
//        }
//        $res = $prize_arr[$prize_id - 1]; //中奖项
//        $data['prize_name'] = $res['name'];
//        $data['prize_site'] = $prize_site;//前端奖项从-1开始
//        echo json_encode($data);
    }
//获取随机数
    private function getRand($proArr){
        $result = '';
        $proSum = array_sum($proArr);
        //概率数组循环
        foreach ($proArr as $key => $proCur) {
            $randNum = mt_rand(1, $proSum);//关键
            if ($randNum <= $proCur) {
                $result = $key;
                break;
            } else {
                $proSum -= $proCur;
            }
        }
        unset ($proArr);
        return $result;
    }
}
<?php
namespace app\explorer\Controller;
use think\Controller;
class ShopController Extends Controller{
    /**
     * 抽奖后台接口
     * @author 武当山道士
     */

    public function ex(){
//        $nick = I('nick','','trim');
//        $avatar = I('avatar','','trim');
//        $openid = I('openid','','trim');
//if(empty($nick)) $this->error('empty nick');
//if(empty($avatar)) $this->error('empty avatar');
//if(empty($openid)) $this->error('empty openid');
        /*自己封装的error函数，正常应该这样写：
        $this->ajaxReturn(array(
        'data'=>'',
        'info'=>$info,
        'status'=>$status
        ));*/

//初始化奖品池，8个奖品，满概率100，最小概率为1(id,name以实际数据库取出的数据为准，percent之和等于100)
        $arr8 = array(
            array("id"=>1,"name"=>"安慰奖","percent"=>69),
            array("id"=>2,"name"=>"玩具车","percent"=>10),
            array("id"=>3,"name"=>"自行车","percent"=>6),
            array("id"=>4,"name"=>"电动车","percent"=>5),
            array("id"=>5,"name"=>"摩托","percent"=>4),
            array("id"=>6,"name"=>"拖拉机","percent"=>3),
            array("id"=>7,"name"=>"夏利","percent"=>2),
            array("id"=>8,"name"=>"奥迪","percent"=>1),
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
        $prize_index = $indexArr[$rand_index];
        $prizeInfo = $arr8[$prize_index];


//        $data['pnum'] = $prize_index;//对应前端奖品编号
//        $data['pid'] = $prizeInfo['id'];
//        $data['pname'] = $prizeInfo['name'];
        $data=[
        'pnum'=>$prize_index,
        'pid'=>$prizeInfo['id'],
        'pname'=>$prizeInfo['name']
        ];
        //$this->success($data);/*自己封装的success，正常应该这样写
        echo json_encode($data);

    }

}

?>
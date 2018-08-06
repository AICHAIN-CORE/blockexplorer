<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/7
 * Time: 14:56
 */
namespace app\explorer\controller;
use think\Controller;
use think\Db;
use think\Request;
class Block extends Controller
{
          public function block($block_number){

              $db =Db::name('Block');
              if(strlen($block_number) ==66){
                  $block_number =substr($block_number,2,66 );
                  $db->where(['block_hash' => $block_number]);
              }else{
                  $db->where(['block_number' => $block_number]);
              }



              $Transaction=Db::name('Transaction')->where(['block_number'=>$block_number])->count();

              $InternalTransaction=Db::name('InternalTransaction')->where(['block_number'=>$block_number])->count();

              $Block=$db->select();

             // $TotalDifficulty = $db->sum('difficulty');
              setlocale(LC_MONETARY,"en_US");


              $Block[0]['timeago'] = getTimeDiff($Block[0]['block_timestamp'],'en');
              $Block[0]['transaction_num']  = $Transaction;
              $Block[0]['internaltransaction_num'] = $InternalTransaction;
              $Block[0]['difficulty'] = number_format($Block[0]['difficulty']);
           //   $Block[0]['totladifficulty'] = number_format($TotalDifficulty);

              $up_number = $Block[0]['block_number']-1;
              $down_number = $Block[0]['block_number']+1;


              $this->assign([
                  'Block'  =>$Block[0],
                  'up_number'=>$up_number,
                  'down_number'=>$down_number
              ]);

              return $this->fetch();
          }

    public function blocks(){



              if(isset($_GET['listRows'])){
                  $listRows = $_GET['listRows'];
              }else{
                  $listRows=20;
              }

        $num = Db::table("Block")->order('block_number desc')->limit(1)->select()[0];//统计一共拥有的条数


        $Block = Db::name('Block')->order('block_number desc')->paginate($listRows,$num['block_number'],['query' => Request::instance()->param()])->each(function($item, $key){
            $Transaction=Db::name('Transaction')->where(['block_number'=>$item['block_number']])->count();
            //$uncle_num=Db::name('Transaction')->where(['txhash'=>$item['uncle_hash']])->count();
            $item['timeago'] = getTimeDiff( $item['block_timestamp'],'en');
            $item['transaction_num']  = $Transaction;
//          $Block[0]['internaltransaction_num'] = $InternalTransaction;
            $item['difficulty'] = number_format( $item['difficulty']);
            $item['uncle_num'] = 0;
            return $item;
            });
        $page = $Block->render();
        $this->assign([
            'Blocks'  =>$Block,
            'page'=>$page,
            'listRows'=>$listRows
        ]);


        return $this->fetch();
    }


}

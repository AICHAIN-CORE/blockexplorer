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
class Transactions extends Controller
{
         public function transactions(){

             $db=Db::name('Transaction');
             if(isset($_REQUEST['block'])){
              $block_number = $_REQUEST['block'];
              $db->where(['block_number'=>$block_number]);
             }

             if(isset($_REQUEST['address'])){
                 $address_from = $_REQUEST['address'];
                 $address_from =substr($address_from,2,42 );
                 $db->where(['from_address'=>$address_from]);
                 $db->whereOr(['to_address'=>$address_from]);
             }


             if(isset($_GET['listRows'])){
                 $listRows = $_GET['listRows'];
             }else{
                 $listRows=20;
             }
             $Transaction = $db->order('block_timestamp desc')->paginate($listRows,false,['query' => Request::instance()->param()])->each(function($item, $key){

             $item['timeago'] = getTimeDiff( $item['block_timestamp'],'en');
                 // $Block[0]['internaltransaction_num'] = $InternalTransaction;
                 //判断是否是只能合约或者erc20
                 $Erc20= Db::name('Erc20Transfer')->where(['txhash'=>$item['txhash']])->select();
                 $item['is_erc20'] = count($Erc20);
                 return $item;
             });

             $page = $Transaction->render();
             $returnArray = [
                 'Transaction'  =>$Transaction,
                 'page'=>$page,
                 'listRows'=>$listRows

             ];
             if(isset($block_number)){
                 $returnArray['block_number']=$block_number;
             }else{
                 $returnArray['block_number']='';
             }

              if(isset($address_from)){
                  $returnArray['address']=$address_from;
              }else{
                  $returnArray['address']='';
              }

             $this->assign($returnArray);


             return $this->fetch();

         }

    public function transactionsinfo($txhash){

        $db=Db::name('Transaction');
        if(isset($txhash)){
            $txhash =substr($txhash,2,66 );
            $db->where(['txhash'=>$txhash]);
        }

        $Transaction = $db->select()[0];
        $Token_Transfer = Db::name('Erc20Transfer')->where(['txhash'=>$Transaction['txhash']])->select();
        if(!empty($Token_Transfer)){
            for($i=0;$i<count($Token_Transfer);$i++){

                $Erc20= Db::name('Contract')->where(['contract_address'=>$Token_Transfer[$i]['contract_address']])->select();
                $Token_Transfer[$i]['token_name']=$Erc20[0]['token_name'];
            }
        }
        $Internal_Transaction = Db::name('InternalTransaction')->where(['parent_txhash'=>$Transaction['txhash']])->select();

        if(isset($Transaction['contract_address'])){
        $recipt = Db::name('Receipt')->where(['contract_address'=>$Transaction['contract_address'],'txhash'=>$txhash])->select();
        if(isset($recipt[0])) {
            for ($i = 0; $i < count($recipt); $i++) {
                $recipt[$i]['logs'] = object_array(json_decode($recipt[$i]['logs']));
            }
            $Eventlogsnumber =count($recipt);
        }
        }else{
            $Eventlogsnumber=0;
        }




        $returnArray = [
            'Transaction'  =>$Transaction,
            'TokenTransfer'=>$Token_Transfer,
            'InternalTransaction'=>$Internal_Transaction,
            'InternalTransactionnum'=>count($Internal_Transaction),
            'Eventlogsnumber'=>$Eventlogsnumber,


        ];

        if(isset($recipt[0])){
            $returnArray['Recipt']=$recipt;
        }else{
            $returnArray['Recipt']=null;
        }

        $this->assign($returnArray);


        return $this->fetch();

    }
    public function txsInternal(){
        $db=Db::name('InternalTransaction');

        $Transaction = $db->order('tx_timestamp desc')->paginate(20,false,request()->param())->each(function($item, $key){

               $list = Db::name('InternalTransaction')->where(['block_number'=>$item['block_number']])->select();
               $item['list']=$list;
               $item['timeage']=getTimeDiff($item['tx_timestamp'],'en');
            return $item;
        });

        $page = $Transaction->render();
        $returnArray = [
            'Transaction'  =>$Transaction,
            'page'=>$page,

        ];

        $this->assign($returnArray);

        return $this->fetch();

    }
    public function txsPending(){
        $db=Db::name('PooledTransaction');
        $Transaction = $db->order('last_seen desc')->paginate(20,false,request()->param())->each(function($item, $key){

            return $item;
        });

        $page = $Transaction->render();
        $returnArray = [
            'Transaction'  =>$Transaction,
            'page'=>$page,

        ];
        $this->assign($returnArray);

       return $this->fetch();
    }

}
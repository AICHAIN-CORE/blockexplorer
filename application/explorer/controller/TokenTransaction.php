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
class TokenTransaction extends Controller
{
         public function tokentransactions(){

             $db=Db::name('Erc20Transfer');
             if(isset($_REQUEST['block'])){
              $block_number = $_REQUEST['block'];
              $db->where(['block_number'=>$block_number]);
             }

             if(isset($_REQUEST['address'])){
                 $address_from = $_REQUEST['address'];
                 $address_from =substr($address_from,2,42 );
                 $db->where(['erc20_from'=>$address_from]);
                 $db->whereOr(['erc20_to'=>$address_from]);
             }


             if(isset($_GET['listRows'])){
                 $listRows = $_GET['listRows'];
             }else{
                 $listRows=20;
             }
             $TokenTransaction = $db->order('tx_timestamp desc')->paginate($listRows,false,['query' => Request::instance()->param()])->each(function($item, $key){

                 $item['timeago'] = getTimeDiff( $item['tx_timestamp'],'en');
                 // $Block[0]['internaltransaction_num'] = $InternalTransaction;
                 //判断是否是只能合约或者erc20
                 $Erc20= Db::name('Contract')->where(['contract_address'=>$item['contract_address']])->select();
                 $item['token'] = $Erc20[0]['token_name'];
                 return $item;
             });

             $page = $TokenTransaction->render();
             $returnArray = [
                 'TokenTransaction'  =>$TokenTransaction,
                 'page'=>$page,
                 'listRows'=>$listRows

             ];
             if(isset($block_number)){
                 $returnArray['block_number']=$block_number;
             }else{
                 $returnArray['block_number']='';
             }
             if(isset($address_from)){
                 $returnArray['address_from']=$address_from;
             }else{
                 $returnArray['address_from']='';
             }

             $this->assign($returnArray);

             return $this->fetch();

         }

         public function gettokentransactions(){

            $db=Db::name('Erc20Transfer');

            /*
            if(isset($_REQUEST['block'])){
             $block_number = $_REQUEST['block'];
             $db->where(['block_number'=>$block_number]);
            }
*/
            $address_contract = '';
            $address_from = '';

            if(isset($_REQUEST['contract_address'])){
                $address_contract = $_REQUEST['contract_address'];
                $address_contract =substr($address_contract,2,42 );
            //    $db->where(['contract_address'=>$address_contract]);
            }
            else {
                $errorMsg = 'parameter: contract_address not found!';
                $returnArray = [
                    'ERROR'  =>$errorMsg,
                ];

                echo json_encode($returnArray);
                return;
            }

            if(isset($_REQUEST['address'])){
                $address_from = $_REQUEST['address'];
                $address_from =substr($address_from,2,42 );
            //    $db->where(['erc20_from'=>$address_from]);
            //    $db->whereOr(['erc20_to'=>$address_from]);
            }
            else {
                $errorMsg = 'parameter: address not found!';
                $returnArray = [
                    'ERROR'  =>$errorMsg,
                ];

                echo json_encode($returnArray);
                return;
            }

            $db->where("contract_address='".$address_contract."' and ( erc20_from='".$address_from."' or erc20_to='".$address_from."')");

            if(isset($_GET['listRows'])){
                $listRows = $_GET['listRows'];
            }else{
                $listRows=20;
            }
            $TokenTransaction = $db->order('tx_timestamp desc')->paginate($listRows,false,['query' => Request::instance()->param()])->each(function($item, $key){

                $item['timeago'] = getTimeDiff( $item['tx_timestamp'],'en');
                // $Block[0]['internaltransaction_num'] = $InternalTransaction;
                //判断是否是只能合约或者erc20
                $Erc20= Db::name('Contract')->where(['contract_address'=>$item['contract_address']])->select();
                $item['token'] = $Erc20[0]['token_name'];
                return $item;
            });

            //$page = $TokenTransaction->render();
            $returnArray = [
                'TokenTransaction'  =>$TokenTransaction,
            //    'page'=>$page,
            //    'listRows'=>$listRows

            ];

            $returnArray['contract_address']=$address_contract;           
            $returnArray['address']=$address_from;

            echo json_encode($returnArray);

        }

    public function transactionsinfo($txhash){

        $db=Db::name('Transaction');
        if(isset($txhash)){
            $txhash =substr($txhash,2,66 );
            $db->where(['txhash'=>$txhash]);
        }

        $Transaction = $db->select()[0];
        $Token_Transfer = Db::name('Erc20Transfer')->where(['txhash'=>$Transaction['txhash']])->select();
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


}
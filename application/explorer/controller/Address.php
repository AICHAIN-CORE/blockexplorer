<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/11
 * Time: 10:02
 */

namespace app\explorer\controller;


use think\Controller;
use think\Db;

class Address extends Controller
{
              public function address($address_hash){


                  $address_hash =substr($address_hash,2,42 );

                  $db=Db::name('Transaction');
                  $balance = Db::name('Balance')->where(['ait_address'=>$address_hash])->select();
                  if(isset($balance[0])){
                      $balance_value = $balance[0]['balance_value'];
                  }else{
                      $balance_value = '';
                  }
                  //交易总数
                  $Transactionsnum = $db->where(['from_address'=>$address_hash])->whereOr(['to_address'=>$address_hash])->count();

                  $ValueAll = $db->sum('amount');




                  $addressList=$db->where(['from_address'=>$address_hash])->whereOr(['to_address'=>$address_hash])->order('block_timestamp desc')->paginate(20,false,request()->param())->each(function($item, $key){
                      $item['timeago'] = getTimeDiff( $item['block_timestamp'],'en');
                      if($item['to_address']==''){
                          $contract = Db::name('Contract')->where(['tx_hash'=>$item['txhash']])->select();
                          $item['to_address'] = $contract[0]['contract_address'];
                      }
                      return $item;
                  });
                  $erc20TransferList=Db::name('Erc20Transfer')->where(['erc20_from'=>$address_hash])->whereOr(['erc20_to'=>$address_hash])->order('tx_timestamp desc')->paginate(20,false,request()->param())->each(function($item, $key){
                      $item['timeago'] = getTimeDiff( $item['tx_timestamp'],'en');
                      $Erc20= Db::name('Contract')->where(['contract_address'=>$item['contract_address']])->select();
                      $item['token'] = $Erc20[0]['token_name'];
                      return $item;

                  });
                  $token_balance = Db::name('TokenBalance')->where(['ait_address'=>$address_hash])->select();
                  if(!empty($token_balance)){
                  for($i=0;$i<count($token_balance);$i++){
                      $contract = Db::name('Contract')->where(['contract_address'=>$token_balance[$i]['contract_address']])->select();
                      $token_balance[$i]['token_name'] = $contract[0]['token_name'];
                   }
                  }
                  $internaltransaction=Db::name('InternalTransaction')->where(['from_address'=>$address_hash])->whereOr(['to_address'=>$address_hash])->order('tx_timestamp desc')->paginate(20,false,request()->param())->each(function($item, $key){
                      $item['timeago'] = getTimeDiff( $item['tx_timestamp'],'en');
//                      $block = Db::name('Block')->where(['block_number'=>$item['block_number']])->select();
//
//                      if(isset($block)){
//                          print_r($block);
//                         // $item['parent_txhash'] = $block[0]['parent_txhash'];
//                      }

                      return $item;

                  });
                  $internaltransactionnum = count($internaltransaction);

                  $returnArray = [
                      'AddressList'  =>$addressList,
                      'address'=>$address_hash,
                      'balance_value'=>$balance_value,
                      'transactionsnum'=>$Transactionsnum,
                      'ValueAll'=>$ValueAll,
                      'erc20TransferList'=>$erc20TransferList,
                      'internaltransaction'=>$internaltransaction,
                      'internaltransactionnum'=>$internaltransactionnum,
                      'token_blance'=>$token_balance

                  ];

                  //判断当前地址是否是合约地址：Contract address

                  $contract_address = Db::name('Contract')->where(['contract_address'=>$address_hash])->select();
                  if(isset($contract_address[0])){
                      $returnArray['contract_address'] = $contract_address[0];
                      $returnArray['is_contract'] = 'true';
                  }else{
                      $returnArray['contract_address'] = [];
                      $returnArray['is_contract'] = 'false';
                  }

                  $this->assign($returnArray);



                  return $this->fetch();
              }

              public function queryerc20address(){

                $address_contract = '';
                if(isset($_REQUEST['contract_address'])){
                    $address_contract = $_REQUEST['contract_address'];
                    $address_contract =substr($address_contract,2,42 );
                }
                else {
                    $errorMsg = 'parameter: contract_address not found!';
                    $returnArray = [
                        'ERROR'  =>$errorMsg,
                    ];
    
                    echo json_encode($returnArray);
                    return;
                }

                $contract_info = Db::name('Contract')->where(['contract_address'=>$address_contract])->select();

                if(isset($contract_info[0])){
                    $returnArray['contract_info'] = $contract_info;
                    $returnArray['is_contract'] = 'true';
                }else{
                    $returnArray['contract_info'] = 'not found';
                    $returnArray['is_contract'] = 'false';
                }

                echo json_encode($returnArray);
                return;
            }

}
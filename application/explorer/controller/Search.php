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

class Search extends Controller
{
              public function Search(){

                $search = $_REQUEST['search'];
                if(isset($search)){
                    if(is_numeric($search)){
                        $this->redirect('/block/'.$search);
                    }

                    if(strlen($search) == 42){

                        $this->redirect('/address/'.$search);
                    }
//                    if(strlen($search) == 40){
//                        $this->redirect('/address/'.$search);
//                    }
                    if(strlen($search) == 66){
                        $this->redirect('/tx/'.$search);
                    }
//                    if(strlen($search) == 64){
//                        $this->redirect('/tx/'.$search);
//                    }

                }

                $this->assign('search',$search);
                //正则  txhash  64字符   地址40    other:block_number
                  return $this->fetch();


              }

}

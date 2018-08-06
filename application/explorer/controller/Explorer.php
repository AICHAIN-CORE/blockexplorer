<?php
namespace app\explorer\controller;
use think\Controller;
use think\Db;
class Explorer extends Controller
{
    public function index()
    {

      $Block=Db::name('Block')->limit('0','10')->order('block_number desc')->select();
      $Transaction=Db::name('Transaction')->limit('0','10')->order('block_timestamp desc')->select();


      for($i = 0;$i<count($Block);$i++){
          $Block[$i]['transaction_num'] = Db::name('Transaction')->where(['block_number'=>$Block[$i]['block_number']])->count();
          $Block[$i]['block_timestamp'] = getTimeDiff($Block[$i]['block_timestamp'],'en');
      }



      for($i = 0;$i<count($Transaction);$i++){

            $Transaction[$i]['block_timestamp'] = getTimeDiff($Transaction[$i]['block_timestamp'],'en');

      }

        $this->assign([
            'Block'  =>$Block,
            'Transaction'=>$Transaction
        ]);



        return $this->fetch();
        //return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="ad_bd568ce7058a1091"></think>';
    }
}

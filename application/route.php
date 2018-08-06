<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[:/]'     => [
        '' => ['explorer/Explorer/index'],//notify_profitchange
    ],
    'block/:block_number'=>['explorer/Block/block',['method'=>'get',]],
    'blocks'=>'explorer/Block/blocks',
    'txs'=>'explorer/Transactions/transactions',
    'address/:address_hash'=> ['explorer/Address/address',['method'=>'get']],
    'tx/:txhash'=>['explorer/Transactions/transactionsinfo',['method'=>'get']],
    'txsInternal'=>['explorer/Transactions/txsInternal',['method'=>'get']],
    'txsPending'=>['explorer/Transactions/txsPending',['method'=>'get']],
    'search'=>'explorer/Search/Search',
    'tokentxs'=>'explorer/TokenTransaction/tokentransactions',
    '[:]' => [
        'block/[:block_number]'=>['explorer/Block/block',['method'=>'get',]],
        'blocks'=>['explorer/Block/blocks'],
        'txs'=>['explorer/Transactions/transactions'],
        'address/[:address_hash]'=>['explorer/Address/address',['method'=>'get']],
        'tx/[:txhash]'=>['explorer/Transactions/transactionsinfo',['method'=>'get']],
        'txsInternal'=>['explorer/Transactions/txsInternal',['method'=>'get']],
        'txsPending'=>['explorer/Transactions/txsPending',['method'=>'get']],
        'search'=>['explorer/Search/Search'],
    ]

];

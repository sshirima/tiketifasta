<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 10/24/2018
 * Time: 3:31 PM
 */

return [
    'servers'=>[
        '41.217.203.61'=>[
            'ip'=>'41.217.203.61',
            'name'=>'Mpesa C2B server',
            'port'=>'30009',
            'status'=>false
        ],
        '41.217.203.241'=>[
            'ip'=>'41.217.203.241',
            'name'=>'Mpesa B2C server',
            'port'=>'30009',
            'status'=>false
        ],
        '41.223.4.174'=>[
            'ip'=>'41.223.4.174',
            'name'=>'Vodacom SMS server',
            'port'=>'6691',
            'status'=>false
        ],
        '41.222.176.143'=>[
            'ip'=>'41.222.176.233',
            'name'=>'Tigo pesa B2C server production',
            'port'=>'6691',
            'status'=>false
        ],
        '41.222.176.233'=>[
            'ip'=>'41.222.176.233',
            'name'=>'Tigo pesa B2C server testing',
            'port'=>'6691',
            'status'=>false
        ],
        '41.222.182.51'=>[
            'ip'=>'41.222.176.143',
            'name'=>'Tigo SMS server',
            'port'=>'10501',
            'status'=>false
        ],
        '127.0.0.1'=>[
            'ip'=>'127.0.0.1',
            'name'=>'Local host server',
            'port'=>'443',
            'status'=>'true'
        ],
    ],

    'notifications'=>[
        'sms'=>[
            'allowed'=>env('MONITOR_SYSTEM_NOTIFICATION_SMS_ALLOW', true),
            'recipients'=>[
                [
                    'number'=>'255754710618',
                    'operator'=>'vodacom',
                ],
                /*[
                    'number'=>'255714682070',
                    'operator'=>'tigo',
                ],*/
            ],
        ],

    ]
];
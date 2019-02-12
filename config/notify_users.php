<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 10/24/2018
 * Time: 3:31 PM
 */

return [
    'sms'=>[
        'sender_id'=>env('SMPP_SENDER_ID'),
        'operators'=>[
            'tigo'=>[
                'system_id'=>env('TIGO_SMPP_SYSTEM_ID'),
                'password'=>env('TIGO_SMPP_PASSWORD'),
                'port'=>env('TIGO_SMPP_PORT'),
                'host'=>env('TIGO_SMPP_HOST'),
            ],
            'vodacom'=>[
                'system_id'=>env('VODA_SMPP_SYSTEM_ID'),
                'password'=>env('VODA_SMPP_PASSWORD'),
                'port'=>env('VODA_SMPP_PORT'),
                'host'=>env('VODA_SMPP_HOST'),
            ]
        ]
    ]
];
<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 10/24/2018
 * Time: 3:31 PM
 */

return [
    'format'=>'Dear %s, You have booked Bus number:%s, From:%s to %s, Travelling on:%s, Ticket ref:%s.Thank you',
    'voda'=>[],
    'tigo'=>[
        'snmp'=>[
            'account'=>[
                'username'=>env('TIGO_SMPP_USERNAME'),
                'password'=>env('TIGO_SMPP_PASSWORD'),
                'port'=>env('TIGO_SMPP_PORT'),
                'host'=>env('TIGO_SMPP_HOST'),
            ],
            'settings'=>[
                'sender'=>env('TIGO_SMPP_SENDER'),
                'source_ton'=>env('TIGO_SMPP_SOURCE_NPI'),
                'destination_ton'=>env('TIGO_SMPP_SOURCE_TON'),
                'source_npi'=>env('TIGO_SMPP_SENDER'),
                'destination_npi'=>env('TIGO_SMPP_DESTINATION_NPI'),
            ],
        ],
    ],
    'airtel'=>[],
];
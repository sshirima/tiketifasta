<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 10/24/2018
 * Time: 1:48 PM
 */

return[
    'mpesa'=>[
        'account_name'=>env('MPESA_ACCOUNT_NAME'),

        'c2b'=>[
            'url_confirm'=>env('MPESA_C2B_CONFIRM'),
        ],
        'b2c'=>[
            'spid'=>env('MPESA_B2C_SPID'),
            'sppassword'=>env('MPESA_B2C_PASSWORD'),
            'url_initiate'=>env('MPESA_B2C_URL'),
            'initiator'=>env('MPESA_B2C_INITIATOR'),
            'initiator_password'=>env('MPESA_B2C_INITIATOR_PASSWORD'),
        ],
    ],
    'tigo'=>[
        'c2b'=>[

        ],
        'bc2'=>[
            'mfi'=>env('TIGO_B2C_MFI'),
            'pin'=>env('TIGO_B2C_PIN'),
            'language'=>env('TIGO_B2C_LANGUAGE'),
            'type'=>env('TIGO_B2C_TYPE'),
        ],
    ],
    'airtel'=>[],
];
<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 10/24/2018
 * Time: 1:48 PM
 */

return[
    'tickets_commission_percentage'=>env('TICKET_COMMISSION_PERCENTAGE'),
    'mpesa'=>[
        'account_name'=>env('MPESA_ACCOUNT_NAME'),

        'c2b'=>[
            'confirm_transaction_url'=>env('MPESA_C2B_CONFIRM'),
        ],
        'b2c'=>[
            'spid'=>env('MPESA_B2C_SPID'),
            'sppassword'=>env('MPESA_B2C_PASSWORD'),
            'url_initiate'=>env('MPESA_B2C_URL'),
            'initiator'=>env('MPESA_B2C_INITIATOR'),
            'initiator_password'=>env('MPESA_B2C_INITIATOR_PASSWORD'),
            'command_id'=>env('MPESA_B2C_COMMAND_ID'),
            'timeout'=>env('MPESA_B2C_TIMEOUT'),
            'connect_timeout'=>env('MPESA_B2C_CONNECT_TIMEOUT'),
        ],
    ],
    'tigo'=>[
        'c2b'=>[
            'key'=>env('TIGOSECURE_KEY'),
            'secret'=>env('TIGOSECURE_SECRET'),
            'url_token'=>env('TIGOSECURE_URL_TOKEN'),
            'url_authorize'=>env('TIGOSECURE_URL_AUTHORIZE'),
        ],
        'bc2'=>[
            'url'=>env('TIGO_B2C_URL'),
            'mfi'=>env('TIGO_B2C_MFI'),
            'pin'=>env('TIGO_B2C_PIN'),
            'language'=>env('TIGO_B2C_LANGUAGE'),
            'type'=>env('TIGO_B2C_TYPE'),
            'confirm_otp'=>env('TIGO_B2C_CONFIRM_OTP'),
            'otp_message'=> env('TIGO_B2C_OTP_MESSAGE'),
            'otp_max_reentry'=> env('TIGO_B2C_OTP_MAX_REENTRY'),
            'timeout'=>env('MPESA_B2C_TIMEOUT'),
            'connect_timeout'=>env('MPESA_B2C_CONNECT_TIMEOUT'),
        ],
    ],
    'airtel'=>[],
];
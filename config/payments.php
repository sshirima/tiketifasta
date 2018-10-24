<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 10/24/2018
 * Time: 1:48 PM
 */

return[
    'mpesa'=>[
        'c2b'=>[

        ],
        'b2c'=>[

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
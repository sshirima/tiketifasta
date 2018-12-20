<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 10/24/2018
 * Time: 3:31 PM
 */

return [
    'format'=>'Dear %s, You have booked Bus number:%s, From:%s to %s, Travelling on:%s, Ticket ref:%s.Thank you',
    'smpp_sender_id'=>env('SMPP_SENDER_ID'),
    'voda_smpp_system_id'=>env('VODA_SMPP_SYSTEM_ID'),
    'voda_smpp_password'=>env('VODA_SMPP_PASSWORD'),
    'voda_smpp_port'=>env('VODA_SMPP_PORT'),
    'voda_smpp_host'=>env('VODA_SMPP_HOST'),
    'tigo_smpp_system_id'=>env('TIGO_SMPP_SYSTEM_ID'),
    'tigo_smpp_password'=>env('TIGO_SMPP_PASSWORD'),
    'tigo_smpp_port'=>env('TIGO_SMPP_PORT'),
    'tigo_smpp_host'=>env('TIGO_SMPP_HOST'),

];
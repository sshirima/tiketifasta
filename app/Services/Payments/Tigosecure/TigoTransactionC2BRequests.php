<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 8/6/2018
 * Time: 6:27 PM
 */

namespace App\Services\Payments\Tigosecure;


use App\Models\TigoOnlineC2B;

trait TigoTransactionC2BRequests
{

    protected function systemStatusOptions(){
        return [
            'headers' =>
                [
                    'accessToken' => $this->getAccessToken()
                ],

        ];
    }

    protected function paymentAuthorizationContent(TigoOnlineC2B $tigoOnlineC2B, $accessToken){
        return [
            'MasterMerchant' => [
                'account' => env('TIGOSECURE_ACCOUNT'),
                'pin' => env('TIGOSECURE_PIN'),
                'id' => env('TIGOSECURE_ID'),
            ],
            'Subscriber' => [
                'account' => $tigoOnlineC2B->phone_number,
                'countryCode' => env('TIGOSECURE_COUNTRY_CODE'),
                'country' => env('TIGOSECURE_COUNTRY'),
                'firstName' => $tigoOnlineC2B->firstname,
                'lastName' => $tigoOnlineC2B->lastname,
                'emailId' => $tigoOnlineC2B->email,//Optional
            ],
            'redirectUri' => route('api.tigo_secure.confirm_payment'),//route('booking.tigo-secure.confirm')
            'callbackUri' => '',//Optional
            'language' => env('TIGOSECURE_LANG'),
            'terminalId' => '',//Optional
            'originPayment' => [
                'amount' => strval($tigoOnlineC2B->amount),
                'currencyCode' => env('TIGOSECURE_CURRENCY_CODE'),
                'tax' => strval($tigoOnlineC2B->tax),//Tax for the transaction in the origin currency
                'fee' => strval($tigoOnlineC2B->fee),//Fee applied by the Master Merchant for the transaction in the origin currency. This fee is charged from the subscriber and will be shown to the subscriber. If no fee has been applied the field can be set to 0
            ],
            'exchangeRate' => '1',//[optional] Exchange rate between the origin currency (currency of the sending country) and local currency (currency of the receiving country)
            'LocalPayment' => [
                'amount' => strval($tigoOnlineC2B->amount),
                'currencyCode' => env('TIGOSECURE_CURRENCY_CODE'),
            ],
            'transactionRefId' => $tigoOnlineC2B->reference
        ];
    }

    protected function validateMFSAccountContent($transactionId, $msisdn, $firstname, $lastname){
        return [
            'transactionRefId'=>$transactionId,
            'ReceivingSubscriber'=>[
                'account'=>$msisdn,
                'countryCallingCode'=>env('TIGOSECURE_COUNTRY_CODE'),
                'countryCode'=>env('TIGOSECURE_COUNTRY'),
                'firstName'=>$firstname,
                'lastName'=>$lastname,
            ]
        ];
    }

    /**
     * @return string
     */
    private function accessTokenRequestParameters()
    {
        $postData  = "client_id=".config('payments.tigo.c2b.key');
        $postData .= "&client_secret=".config('payments.tigo.c2b.secret');
        return $postData;
    }

    /*protected function paymentAuthorizationOptions(TigoOnlineC2B $tigoOnlineC2B, $accessToken){
        return [
            'headers' => [
                'content-type' => 'application/json',
                'accessToken' => $accessToken
            ],
            'form_params'=>[
                'MasterMerchant' => [
                    'account' => env('TIGOSECURE_ACCOUNT'),
                    'pin' => env('TIGOSECURE_PIN'),
                    'id' => env('TIGOSECURE_KEY2'),
                ],
                'Subscriber' => [
                    'account' => $tigoOnlineC2B->phone_number,
                    'countryCode' => env('TIGOSECURE_COUNTRY_CODE'),
                    'country' => env('TIGOSECURE_COUNTRY'),
                    'firstName' => $tigoOnlineC2B->firstname,
                    'lastName' => $tigoOnlineC2B->lastnme,
                    'emailId' => $tigoOnlineC2B->email,//Optional
                ],
                'redirectUri' => route('api.tigo_secure.confirm_payment'),
                'callbackUri' => '',//Optional
                'language' => env('TIGOSECURE_LANG'),
                'terminalId' => '',//Optional
                'originPayment' => [
                    'amount' => $tigoOnlineC2B->amount,
                    'currencyCode' => env('TIGOSECURE_CURRENCY_CODE'),
                    'tax' => '0',//Tax for the transaction in the origin currency
                    'fee' => '0',//Fee applied by the Master Merchant for the transaction in the origin currency. This fee is charged from the subscriber and will be shown to the subscriber. If no fee has been applied the field can be set to 0
                ],
                'exchangeRate' => '0',//[optional] Exchange rate between the origin currency (currency of the sending country) and local currency (currency of the receiving country)
                'LocalPayment' => [
                    'originPayment' => $tigoOnlineC2B->amount,
                    'currencyCode' => env('TIGOSECURE_CURRENCY_CODE'),
                ],
                'transactionRefId' => $tigoOnlineC2B->reference
            ]
        ];
    }*/
}
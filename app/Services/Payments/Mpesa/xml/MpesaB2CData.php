<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 9/11/2018
 * Time: 2:05 PM
 */

namespace App\Services\Payments\Mpesa\xml;


trait MpesaB2CData
{

    public function b2cPaymentConfirmRequest(array $values){
        $xmlDoc = new \SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><brokerRequest version="2.0" xmlns="http://infowise.co.tz/broker/"></brokerRequest>');
        //$result = $xmlDoc->addChild('result');
        $serviceProvider = $xmlDoc->addChild('serviceProvider');
        $serviceProvider->addChild('spId',$values['spId']);
        $serviceProvider->addChild('spPassword',$values['spPassword']);
        $serviceProvider->addChild('timestamp',$values['timestamp']);

        $transaction = $xmlDoc->addChild('transaction');
        $transaction->addChild('amount',$values['amount']);
        $transaction->addChild('commandID',$values['commandID']);
        $transaction->addChild('initiator',$values['initiator']);
        $transaction->addChild('initiatorPassword',$values['initiatorPassword']);
        $transaction->addChild('recipient',$values['recipient']);
        $transaction->addChild('transactionDate',$values['transactionDate']);
        $transaction->addChild('transactionID',$values['transactionID']);
        $transaction->addChild('originatorConversationID',$values['originatorConversationID']);

        return $xmlDoc->asXML();
    }
}
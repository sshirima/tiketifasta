<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 8/3/2018
 * Time: 7:28 PM
 */

namespace App\Services\Payments\Mpesa\xml;


trait MpesaC2BData
{

    public function validatePaymentResponseC2B(array $values){
        $xmlDoc = new \SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><mpesaBroker version="2.0" xmlns="http://infowise.co.tz/broker/"></mpesaBroker>');
        $response = $xmlDoc->addChild('response');
        $response->addChild('conversationID',$values['conversationID']);
        $response->addChild('originatorConversationID',$values['originatorConversationID']);
        $response->addChild('responseCode',$values['responseCode']);
        $response->addChild('responseDesc',$values['responseDesc']);
        $response->addChild('serviceStatus',$values['serviceStatus']);
        $response->addChild('transactionID',$values['transactionID']);
        return $xmlDoc->asXML();
    }

    public function c2bPaymentConfirmRequest(array $values){
        $xmlDoc = new \SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><mpesaBroker version="2.0" xmlns="http://infowise.co.tz/broker/"></mpesaBroker>');
        $result = $xmlDoc->addChild('result');
        $serviceProvider = $result->addChild('serviceProvider');
        $serviceProvider->addChild('spId',$values['spId']);
        $serviceProvider->addChild('spPassword',$values['spPassword']);
        $serviceProvider->addChild('timestamp',$values['timestamp']);

        $transaction = $result->addChild('transaction');
        $transaction->addChild('resultType',$values['resultType']);
        $transaction->addChild('resultCode',$values['resultCode']);
        $transaction->addChild('resultDesc',$values['resultDesc']);
        $transaction->addChild('serviceReceipt',$values['serviceReceipt']);
        $transaction->addChild('serviceDate',$values['serviceDate']);
        $transaction->addChild('serviceID',$values['serviceID']);
        $transaction->addChild('originalConversationID',$values['originalConversationID']);
        $transaction->addChild('conversationID',$values['conversationID']);
        $transaction->addChild('transactionID',$values['transactionID']);
        $transaction->addChild('initiator',$values['initiator']);
        $transaction->addChild('initiatorPassword',$values['initiatorPassword']);
        return $xmlDoc->asXML();
    }

    public function c2bServiceConfirmedRequest(array $values){

    }

    public function b2cPaymentRequest(array $values){
        $xmlDoc = new \SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><mpesaBroker version="2.0" xmlns="http://infowise.co.tz/broker/"></mpesaBroker>');
        $request = $xmlDoc->addChild('request');
        $serviceProvider = $request->addChild('serviceProvider');
        $serviceProvider->addChild('spId',$values['spId']);
        $serviceProvider->addChild('spPassword',$values['spPassword']);
        $serviceProvider->addChild('timestamp',$values['timestamp']);

        $transaction = $request->addChild('transaction');
        $transaction->addChild('commandID',$values['commandID']);
        $transaction->addChild('initiator',$values['initiator']);
        $transaction->addChild('initiatorPassword',$values['initiatorPassword']);
        $transaction->addChild('originatorConversationID',$values['originatorConversationID']);
        $transaction->addChild('recipient',$values['recipient']);
        $transaction->addChild('transactionDate',$values['transactionDate']);
        $transaction->addChild('transactionID',$values['transactionID']);

        $requestParameters = $transaction->addChild('requestParameters');
        $parameter = $requestParameters->addChild('parameter');
        $parameter->addChild('key',$values['amount_key']);
        $parameter->addChild('value',$values['amount_value']);

        return $xmlDoc->asXML();
    }
}
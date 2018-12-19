<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 8/3/2018
 * Time: 7:28 PM
 */

namespace App\Services\Payments\Mpesa;


trait MpesaTransactionC2BRequest
{

    /**
     * @param $ticket
     * @param $mpesaC2B
     * @param $spPassword
     * @param $timestamp
     * @return array
     */
    private function getMpesaC2BConfirmRequestArray($ticket, $mpesaC2B, $spPassword, $timestamp): array
    {
        return [
            'spId' => env('MPESA_SPID'),
            'spPassword' => $spPassword,
            'timestamp' => $timestamp,
            'resultType' => isset($ticket)?'Completed':'failed',
            'resultCode' => isset($ticket)?'0':'999',
            'resultDesc' => 'Successful',
            'serviceReceipt' => isset($ticket)?$ticket->ticket_ref:null,//Ticket receipt
            'serviceDate' => date('Y-m-d H:i:s'),//Ticket ID
            'serviceID' => isset($ticket)?$ticket->id:null,//Ticket ID
            'originatorConversationID' => $mpesaC2B->og_conversation_id,//Ticket ID
            'conversationID' => $mpesaC2B->conversation_id,//Ticket ID
            'transactionID' => $mpesaC2B->transaction_id,//Ticket ID
            'initiator' => null,//$this->mpesaC2B->reference,//Ticket ID
            'initiatorPassword' => null, //$this->mpesaC2B->reference,//Ticket ID
        ];
    }

    /**
     * @param array $request
     * @return array
     */
    private function getMpesaC2BAuthorizationParamsArray(array $request): array
    {
        return [
            'amount' => $request['request']['transaction']['amount'],
            'account_reference' => $request['request']['transaction']['accountReference'],
            'command_id' => $request['request']['transaction']['commandID'],
            'initiator' => $request['request']['transaction']['initiator'],
            'og_conversation_id' => $request['request']['transaction']['originatorConversationID'],
            'recipient' => $request['request']['transaction']['recipient'],
            'mpesa_receipt' => $request['request']['transaction']['mpesaReceipt'],
            'transaction_date' => $request['request']['transaction']['transactionDate'],
            'transaction_id' => $request['request']['transaction']['transactionID'],
            'conversation_id' => $request['request']['transaction']['conversationID'],
        ];
    }

    /**
     * @param $input
     * @return array
     */
    private function getValidationReponseArray($input): array
    {
        return [
            'conversationID' => $input['request']['transaction']['conversationID'],
            'originatorConversationID' => $input['request']['transaction']['originatorConversationID'],
            'responseCode' => 0,
            'responseDesc' => 'Received',
            'serviceStatus' => 'Success',
            'transactionID' => $input['request']['transaction']['transactionID'],
        ];
    }
    /**
     * @param array $values
     * @return mixed
     */
    public function c2bValidationResponseToXml(array $values){
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

    /**
     * @param array $values
     * @return mixed
     */
    public function c2bConfirmRequestToXml(array $values){
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
        $transaction->addChild('originatorConversationID',$values['originatorConversationID']);
        $transaction->addChild('conversationID',$values['conversationID']);
        $transaction->addChild('transactionID',$values['transactionID']);
        $transaction->addChild('initiator',$values['initiator']);
        $transaction->addChild('initiatorPassword',$values['initiatorPassword']);
        return $xmlDoc->asXML();
    }

    /**
     * @param array $values
     * @return mixed
     */
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
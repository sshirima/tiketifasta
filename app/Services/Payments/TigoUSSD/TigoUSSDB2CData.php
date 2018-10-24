<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 10/23/2018
 * Time: 1:56 PM
 */

namespace App\Services\Payments\TigoUSSD;


trait TigoUSSDB2CData
{

    public function b2cInitiatePaymentData(array $values){
        $command = new \SimpleXMLElement('<?xml version="1.0"?><COMMAND></COMMAND>');
        //$result = $xmlDoc->addChild('result');
        $command->addChild('TYPE',$values['type']);
        $command->addChild('REFERENCEID',$values['referenceId']);
        $command->addChild('MSISDN',$values['msisdn']);
        $command->addChild('PIN',$values['pin']);
        $command->addChild('MSISDN1',$values['msisdn1']);
        $command->addChild('AMOUNT',$values['amount']);
        $command->addChild('LANGUAGE1',$values['language']);
        return $command->asXML();
    }
}
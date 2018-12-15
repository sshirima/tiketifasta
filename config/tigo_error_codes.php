<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 10/24/2018
 * Time: 1:48 PM
 */

return[
    'payment_gateway'=>[
        '00317'=>'Unable to complete transaction as recipient A/c is barred. Error code 00317.',
        '00410'=>'Unable to complete transaction as amount is more than the maximum limit. Error code: 00410.',
        '2117'=>'Unable to complete transaction as sender A/c is barred. Error code 02117.',
        '200'=>'Success',
        '0'=>'Success',
        '60014 '=>'Unable to complete transaction as maximum transaction value per day for payer reached. Error code 60014.',
        '60017 '=>'Unable to complete transaction as transaction amount is less than the minimum txn value for sender. Error code 60017.',
        '60018 '=>'Unable to complete transaction as amount is more than the maximum limit. Error code 60018.',
        '60019 '=>'Unable to complete transaction as account would go below minimum balance. Error code 60019.',
        '60021 '=>'Unable to complete transaction as maximum number of transactions per day for Payee was reached. Error code 60021.',
        '60024 '=>'Unable to complete transaction as maximum transaction value per day reached. Error code 60024.',
        '60028 '=>'Unable to complete transaction as transaction amount is more than the maximum txn value for recipient. Error code 60028.',
        '60030 '=>'Unable to complete transaction as the Payee account would go above maximum balance. Error code: 60030.',
        '60074 '=>'Payee Role Type Transfer Profile not defined',
        '100'=>'This is generic error, which is returned if problem happen during transaction processing. Partner should put transaction amount in HOLD state to avoid risk of rollback while amount was disbursed. This is the same case for any kind of timeout as well.',
    ],
    'tigo_secure'=>[
        'names'=>[
            'Purchase-3008-2501-F'=>'Backend system error',
            'Purchase-3008-2502-F'=>'Transaction timed out',
            'Purchase-3008-3011-E'=>'Unable to complete transaction invalid amount',
            'Purchase-3008-3043-E'=>'Transaction not authorize',
            'Purchase-3008-3045-E'=>'Cancel Transaction',
            'Purchase-3008-0000-S'=>'Successful Payment',
            'validatemfsaccount-3018-4501-V '=>'Invalid Request. Please check the input and resubmit',
            'validatemfsaccount-3018-3001-E '=>'Backend error description',
            'validatemfsaccount-3018-2501-F '=>'One or more back ends may be down. Please try again later.',
            'validatemfsaccount-3018-2502-F '=>'Service call has timed out. Please try again later.',
            'validatemfsaccount-3018-2505-F '=>'Service Authentication Failed.',
            'validatemfsaccount-3018-2506-F '=>'Consumer is not authorized to use this service.',
            'validatemfsaccount-3018-3603-E '=>'Internal service error has occurred.',
            'validatemfsaccount-3018-3999-E '=>'Unknown/Uncaught error has occurred.',
            'validatemfsaccount-3018-4502-V '=>'Invalid ISD code passed in the MSISDN',
            'validatemfsaccount-3018-4503-V '=>'Web Service Implementation is not available for this country',
            'validatemfsaccount-3018-4504-V '=>'Required additional parameters are not passed in the request.',
            'validatemfsaccount-3018-4505-V '=>'Duplicate additional parameters passed in the request.',
            'validatemfsaccount-3018-4506-V '=>'Invalid consumerId passed in the request.',


        ],
        'descriptions'=>[
            'Purchase-3008-2501-F'=>'Backend Error caused the transaction to fail.',
            'Purchase-3008-2502-F'=>'The transaction timed out causing it to fail.',
            'Purchase-3008-3011-E'=>'Unable to complete transaction as amount is invalid',
            'Purchase-3008-3043-E'=>'The customer did not authorize the payment and therefore the transaction failed. This could be caused by the customer not confirming payment, incorrect verification code or PIN code, insufficient balance etc.',
            'Purchase-3008-3045-E'=>'The customer doesn’t wish to complete the transaction and wants to cancel the transaction at its current state',
            'Purchase-3008-0000-S'=>'Successful Payment',
            'validatemfsaccount-3018-4501-V '=>'OSB Validation Error',
            'validatemfsaccount-3018-3001-E '=>'Backend Error',
            'validatemfsaccount-3018-2501-F '=>'Connection Error.',
            'validatemfsaccount-3018-2502-F '=>'Timeout error.',
            'validatemfsaccount-3018-2505-F '=>'OWSM Authentication Failure.',
            'validatemfsaccount-3018-2506-F '=>'OWSM Authentication Failure.',
            'validatemfsaccount-3018-3603-E '=>'Internal service error.',
            'validatemfsaccount-3018-3999-E '=>'Unknown/Uncaught error has occurred.',
            'validatemfsaccount-3018-4502-V '=>'Validation Error',
            'validatemfsaccount-3018-4503-V '=>'Validation Error',
            'validatemfsaccount-3018-4504-V '=>'When the required additional parameters are not passed',
            'validatemfsaccount-3018-4505-V '=>'When the required additional parameters are repeated',
            'validatemfsaccount-3018-4506-V '=>'When the consuming application does not send the Id , which helps middleware to identify the request originating request.',

        ],
    ],

];
<?php


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('sms/send-sms', 'api\SmsController@send')->name('send_sms');

Route::get('mpesa/initialize-payment', 'api\MpesaC2BController@initializePaymentC2B');
Route::post('mpesa/b2c/confirm-transaction', 'api\MpesaB2CController@confirmB2CTransaction')->name('api.mpesa.b2c.confirm_transaction');
Route::get('mpesa/b2c/initiate-transaction', 'api\MpesaB2CController@initiateB2CTransaction')->name('api.mpesa_b2c.initialize_payment');
Route::post('mpesa/validate-payment', 'api\MpesaC2BController@validatePaymentC2B')->name('api.mpesa.validate_payment');
Route::get('mpesa/confirm-payment', 'api\MpesaC2BController@confirmPaymentC2B')->name('api.mpesa.confirm_payment');
Route::post('tiketifasta/C2B-request-url', 'api\MpesaC2BController@validateMpesaC2BTransaction')->name('api.mpesa.confirm_payment');

Route::get('tigo-secure/confirm-payment', 'api\TigoOnlineController@confirmPayment')->name('api.tigo_secure.confirm_payment');
Route::get('tigo-secure/authorize-payment', 'api\TigoOnlineController@authorizePayment')->name('api.tigo_secure.authorize_payment');
Route::post('tigo-secure/confirm-payment', 'api\TigoOnlineController@confirmPayment')->name('api.tigo_secure.confirm_payment');
Route::get('tigo/b2c/initialize-payment', 'api\TigoB2CController@initiateTransaction')->name('api.tigo_b2c.initialize_payment');
Route::get('scheduled-tasks/{id}/after', 'api\ScheduledTasksController@after')->name('api.scheduled_tasks.after');
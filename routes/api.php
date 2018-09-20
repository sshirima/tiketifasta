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

Route::get('mpesa/initialize-payment', 'api\MpesaC2BController@initializePaymentC2B');
Route::get('mpesa/b2c/initialize-payment', 'api\MpesaB2CController@initiateB2CTransaction');
Route::post('mpesa/validate-payment', 'api\MpesaC2BController@validatePaymentC2B')->name('api.mpesa.validate_payment');
Route::get('mpesa/confirm-payment', 'api\MpesaC2BController@confirmPaymentC2B')->name('api.mpesa.confirm_payment');
Route::post('tiketifasta/C2B-request-url', 'api\MpesaC2BController@validatePaymentC2B')->name('api.mpesa.confirm_payment');

Route::get('tigo-secure/confirm-payment', 'api\TigoOnlineController@confirmPayment')->name('api.tigo_secure.confirm_payment');
Route::get('tigo-secure/generate-token', 'api\TigoOnlineController@generateAccessToken')->name('api.tigo_secure.generate_token');
Route::get('tigo-secure/system-status', 'api\TigoOnlineController@serverStatus')->name('api.tigo_secure.system_status');
Route::get('tigo-secure/validate-account', 'api\TigoOnlineController@validateAccount')->name('api.tigo_secure.validate_account');
Route::get('tigo-secure/authorize-payment', 'api\TigoOnlineController@authorizePayment')->name('api.tigo_secure.authorize_payment');
Route::post('tigo-secure/confirm-payment', 'api\TigoOnlineController@confirmPayment')->name('api.tigo_secure.confirm_payment');

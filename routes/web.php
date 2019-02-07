<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/analyse-configuration', 'FileAnalyserController@displayForm')->name('file_analyser_form');
Route::get('/analyse-config-file-all', 'FileAnalyserController@analyseConfigFilesAllRequest')->name('file_analyser_analyse');
Route::get('/get-directories', 'FileAnalyserController@getDirectories');
Route::get('/analyse-config-file', 'FileAnalyserController@analyseConfigFileRequest');

Route::get('/', 'Users\UserController@homepage')->name('user.home');
Route::get('/home', 'Users\UserController@homepage');
Route::get('/about-us', 'Users\UserController@aboutUs')->name('user.about_us');
Route::get('/contact-us', 'Users\UserController@contactUs')->name('user.contact_us');
Route::get('/testimonials', 'Users\UserController@testimonials')->name('user.testimonials');
Route::get('/verify-ticket', 'Users\VerifyTicketController@displayForm')->name('user.verify.ticket.form');
Route::post('/verify-ticket', 'Users\VerifyTicketController@verifyReference')->name('user.verify.ticket.submit');
// Authentication Routes...
Route::get('login', 'Users\Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Users\Auth\LoginController@login')->name('login');
Route::get('logout', 'Users\Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::get('register', 'Users\Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Users\Auth\RegisterController@register')->name('register.submit');

Route::get('search/bus', 'Users\Bookings\SelectBusController@search')->name('booking.buses.search');
Route::get('bus/{b_id}/schedule/{s_id}/trip/{t_id}/select/seat', 'Users\Bookings\SelectBusController@selectBus')->name('booking.buses.select');
Route::get('bus/{b_id}/schedule/{s_id}/trip/{t_id}/booking-info', 'Users\Bookings\SelectBusController@selectSeat')->name('booking.seat.select');
Route::get('bus/{b_id}/schedule/{s_id}/trip/{t_id}/booking/store', 'Users\Bookings\SelectBusController@bookingStore')->name('booking.store');
Route::get('tigo-secure/confirm', 'Users\Bookings\SelectBusController@confirmTigoSecurePayment')->name('booking.tigo-secure.confirm');

// Password Reset Routes...
Route::get('password/change', 'Users\Auth\ChangePasswordController@showForm')->name('password.change');
Route::post('password/change', 'Users\Auth\ChangePasswordController@changePassword')->name('password.update');
Route::get('password/reset', 'Users\Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Users\Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Users\Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Users\Auth\ResetPasswordController@reset')->name('password.reset');

Route::get('auto-complete-query', 'Users\HomepageController@autoCompleteLocationQuery')->name('auto_complete_query');

Route::prefix('user')->group(function () {
    Route::get('dashboard', 'Users\DashboardController@showDashboard')->name('user.dashboard.show');
    Route::get('profile/view', 'User\ProfileController@show')->name('user.profile.show');
    Route::get('profile/edit', 'User\ProfileController@edit')->name('user.profile.edit');
    Route::put('profile/update/{id}', 'User\ProfileController@update')->name('user.profile.update');
});

Route::prefix('admin')->group(function () {
    Route::get('/', 'Admins\AdminController@homepage')->name('admin.home');

    // Authentication Routes...
    Route::get('login', 'Admins\Auth\LoginController@showLoginForm')->name('admin.login');
    Route::post('login', 'Admins\Auth\LoginController@login')->name('admin.login');
    Route::get('logout', 'Admins\Auth\LoginController@logout')->name('admin.logout');

// Registration Routes...
    Route::get('register', 'Admins\Auth\RegisterController@showRegistrationForm')->name('admin.register');
    Route::post('register', 'Admins\Auth\RegisterController@register')->name('admin.register.submit');

// Password Reset Routes...
    Route::get('password/change', 'Admins\Auth\ChangePasswordController@showForm')->name('admin.password.change');
    Route::post('password/change', 'Admins\Auth\ChangePasswordController@changePassword')->name('admin.password.update');
    Route::get('password/reset', 'Admins\Auth\ForgotPasswordController@showLinkRequestForm')->name('admin.password.request');
    Route::post('password/email', 'Admins\Auth\ForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');
    Route::get('password/reset/{token}', 'Admins\Auth\ResetPasswordController@showResetForm')->name('admin.password.reset');
    Route::post('password/reset', 'Admins\Auth\ResetPasswordController@reset')->name('admin.password.reset.change');
//Profile management routes
    Route::get('profile/show', 'Admins\ProfileController@show')->name('admin.profile.show');
    Route::get('profile/edit', 'Admins\ProfileController@edit')->name('admin.profile.edit');
    Route::put('profile/update', 'Admins\ProfileController@update')->name('admin.profile.update');

    //Merchant management routes
    Route::get('merchants', 'Admins\MerchantController@index')->name('admin.merchant.index');
    Route::get('merchants/create', 'Admins\MerchantController@create')->name('admin.merchant.create');
    Route::post('merchants/store', 'Admins\MerchantController@store')->name('admin.merchant.store');
    Route::get('merchants/{id}/show', 'Admins\MerchantController@show')->name('admin.merchant.show');
    Route::get('merchants/{id}/edit', 'Admins\MerchantController@edit')->name('admin.merchant.edit');
    Route::put('merchants/{id}/update', 'Admins\MerchantController@update')->name('admin.merchant.update');
    Route::get('merchants/{id}/delete', 'Admins\MerchantController@delete')->name('admin.merchant.delete');
    Route::delete('merchants/{id}', 'Admins\MerchantController@remove')->name('admin.merchant.remove');
    Route::get('merchants/{id}/authorize', 'Admins\MerchantController@authorizeMerchant')->name('admin.merchant.authorize');
    Route::post('merchants/{id}/enable', 'Admins\MerchantController@enableMerchant')->name('admin.merchant.enable');
    Route::post('merchants/{id}/disable', 'Admins\MerchantController@disableMerchant')->name('admin.merchant.disable');

    //Location CRUD route
    Route::get('locations', 'Admins\LocationController@index')->name('admin.location.index');
    Route::get('locations/create', 'Admins\LocationController@create')->name('admin.location.create');
    Route::post('locations/store', 'Admins\LocationController@store')->name('admin.location.store');
    Route::get('locations/{id}/show', 'Admins\LocationController@show')->name('admin.location.show');
    Route::get('locations/{id}/delete', 'Admins\LocationController@delete')->name('admin.location.delete');
    Route::delete('locations/{id}', 'Admins\LocationController@remove')->name('admin.location.remove');

    //Bus routes routes CRUD
    Route::get('routes', 'Admins\RouteController@index')->name('admin.routes.index');
    Route::get('routes/create', 'Admins\RouteController@create')->name('admin.routes.create');
    Route::post('routes/store', 'Admins\RouteController@store')->name('admin.routes.store');
    Route::get('routes/{id}/show', 'Admins\RouteController@show')->name('admin.routes.show');
    Route::get('routes/{id}/edit', 'Admins\RouteController@edit')->name('admin.routes.edit');
    Route::put('routes/{id}', 'Admins\RouteController@edit')->name('admin.routes.update');
    Route::get('routes/{id}/delete', 'Admins\RouteController@delete')->name('admin.routes.delete');
    Route::delete('routes/{id}remove', 'Admins\RouteController@remove')->name('admin.routes.remove');

    //Bus type management routes
    Route::get('bustype', 'Admins\BusTypeController@index')->name('admin.bustype.index');
    Route::get('bustype/create', 'Admins\BusTypeController@create')->name('admin.bustype.create');
    Route::post('bustype/store', 'Admins\BusTypeController@store')->name('admin.bustype.store');
    Route::get('bustype/{id}/show', 'Admins\BusTypeController@show')->name('admin.bustype.show');
    Route::get('bustype/{id}/delete', 'Admins\BusTypeController@delete')->name('admin.bustype.delete');
    Route::delete('bustype/{id}', 'Admins\BusTypeController@remove')->name('admin.bustype.remove');

    //Buses management routes
    Route::get('buses', 'Admins\BusController@index')->name('admin.buses.index');
    Route::get('buses/create', 'Admins\BusController@create')->name('admin.buses.create');
    Route::post('buses/store', 'Admins\BusController@store')->name('admin.buses.store');
    Route::get('buses/{id}/edit', 'Admins\BusController@edit')->name('admin.buses.edit');
    Route::put('buses/{id}/update', 'Admins\BusController@update')->name('admin.buses.update');
    Route::get('buses/{id}/show', 'Admins\BusController@show')->name('admin.buses.show');
    Route::delete('buses/{id}/destroy', 'Admins\BusController@destroy')->name('admin.buses.destroy');
    Route::get('buses/{id}/authorize', 'Admins\BusController@authorizeBus')->name('admin.buses.authorizes');
    Route::post('buses/{id}/enable', 'Admins\BusController@enableBus')->name('admin.buses.enable');
    Route::post('buses/{id}/disable', 'Admins\BusController@disableBus')->name('admin.buses.disable');

    Route::get('buses/{id}/routes', 'Admins\Buses\BusRouteController@showRoute')->name('admin.buses.route.show');
    Route::get('buses/{id}/schedules', 'Admins\Buses\BusScheduleController@index')->name('admin.buses.schedules');
    Route::get('buses/{id}/schedules/events', 'Merchants\Buses\BusSchedulingController@busSchedules');
    Route::get('buses/{id}/prices', 'Admins\Buses\BusTripController@tripPrice')->name('admin.buses.assign_price');
    Route::get('bookings', 'Admins\BookingController@index')->name('admin.bookings.index');
    Route::get('schedules', 'Admins\ScheduleController@index')->name('admin.schedules.index');
    Route::get('payment-accounts', 'Admins\SystemPaymentController@index')->name('admin.payments-accounts.index');
    Route::get('payment-accounts/create', 'Admins\SystemPaymentController@create')->name('admin.payments-accounts.create');
    Route::post('payment-accounts/store', 'Admins\SystemPaymentController@store')->name('admin.payments-accounts.store');
    Route::get('payment-accounts/{id}/destroy', 'Admins\ScheduleController@destroy')->name('admin.payments-accounts.destroy');

    //Trips
    Route::get('trips', 'Admins\TripsController@index')->name('admin.trips.index');

    Route::get('collection/transactions/all', 'Admins\BookingPaymentController@index')->name('admin.booking_payments.index');
    Route::get('collection/transactions/mpesa', 'Admins\MpesaC2BController@index')->name('admin.mpesac2b.index');
    Route::get('collection/transactions/tigopesa', 'Admins\TigoSecureC2BController@index')->name('admin.tigosecurec2b.index');

    Route::get('merchants-payments/mpesa', 'Admins\MpesaB2CController@index')->name('admin.mpesab2c.index');
    Route::get('merchant-payments/tigopesa', 'Admins\TigoB2CController@index')->name('admin.tigob2c.index');
    Route::get('merchant-payments/tigopesa/send_cash', 'Admins\TigoB2CController@sendCash')->name('admin.tigob2c.send_cash');
    Route::get('merchant-payments/tigopesa/send_cash/submit', 'Admins\TigoB2CController@sendCashSubmit')->name('admin.tigob2c.send_cash.submit');
    Route::post('merchant-payments/tigopesa/send_cash/verify-otp', 'Admins\TigoB2CController@verifyTransactionOTP')->name('admin.tigob2c.send_cash.verify_otp');

    Route::get('merchant-payments/send_cash', 'Admins\B2CTestController@sendCash')->name('admin.mpesab2c.send_cash');
    Route::get('merchant-payments/send_cash/submit', 'Admins\B2CTestController@sendCashSubmit')->name('admin.mpesab2c.send_cash.submit');
    Route::post('merchant-payments/send_cash/verify-otp', 'Admins\B2CTestController@verifyTransactionOTP')->name('admin.mpesab2c.send_cash.verify_otp');


    Route::get('merchant-payments/summary', 'Admins\MerchantPaymentController@summaryReport')->name('admin.merchant_payments.summary');
    Route::get('merchant-scheduled-payments', 'Admins\MerchantSchedulePaymentController@index')->name('admin.merchant_schedule_payments.index');
    Route::get('merchant-scheduled-payments/{id}/details', 'Admins\MerchantSchedulePaymentController@details')->name('admin.merchant_schedule_payments.details');
    Route::get('merchant-payments/{id}/merchant-report', 'Admins\MerchantPaymentController@merchantReport')->name('admin.merchant_payments.merchant_report');
    Route::get('merchant-payments/{id}/bus-report', 'Admins\MerchantPaymentController@busReport')->name('admin.merchant_payments.bus_report');
    Route::get('merchant-payments-accounts/index', 'Admins\MerchantPaymentAccountController@index')->name('admin.merchant_payment_accounts.index');
    Route::get('merchant-payments-accounts/create', 'Admins\MerchantPaymentAccountController@create')->name('admin.merchant_payment_accounts.create');
    Route::get('merchant-payments/retry-payment', 'Admins\MerchantSchedulePaymentController@retryPayment')->name('admin.merchant_payments.retry_payments');
    Route::post('merchant-payments-accounts/store', 'Admins\MerchantPaymentAccountController@store')->name('admin.merchant_payment_accounts.store');
    Route::get('merchant-payments-accounts/{id}/edit', 'Admins\MerchantPaymentAccountController@edit')->name('admin.merchant_payment_accounts.edit');
    Route::put('merchant-payments-accounts/{id}/update', 'Admins\MerchantPaymentAccountController@update')->name('admin.merchant_payment_accounts.update');
    Route::delete('merchant-payments-accounts/{id}/delete', 'Admins\MerchantPaymentAccountController@delete')->name('admin.merchant_payment_accounts.delete');
    Route::delete('merchant-payments-accounts/{id}/destroy', 'Admins\MerchantPaymentAccountController@destroy')->name('admin.merchant_payment_accounts.destroy');

    Route::get('payments/tigopesa/c2b', 'Admins\TigoSecureC2BController@index')->name('admin.tigoonline_c2b.index');
    Route::get('payments/tigopesa/b2c', 'Admins\TigoB2CController@index')->name('admin.tigo_b2c.index');


    //Admin account CRUD routes (V2.0)
    Route::get('accounts/admins', 'Admins\AdminAccountsController@index')->name('admin.admin_accounts.index');
    Route::get('accounts/admins/create', 'Admins\AdminAccountsController@create')->name('admin.admin_accounts.create');
    Route::post('accounts/admins/create', 'Admins\AdminAccountsController@store')->name('admin.admin_accounts.store');
    Route::delete('accounts/admins/{id}/delete', 'Admins\AdminAccountsController@destroy')->name('admin.admin_accounts.destroy');

    Route::get('sms/view', 'Admins\SentSMSController@index')->name('admin.sent_sms.index');
    Route::get('sms/send', 'Admins\SentSMSController@sendSMS')->name('admin.sms.send');
    Route::post('sms/send', 'Admins\SentSMSController@sendSMSSubmit')->name('admin.sms.send.submit');
    Route::get('bookings', 'Admins\BookingController@index')->name('admin.bookings.index');
    //Tickets
    Route::get('tickets', 'Admins\TicketController@index')->name('admin.tickets.index');
    Route::get('scheduled-tasks', 'Admins\ScheduleTasksController@index')->name('admin.scheduled_tasks.index');
    Route::get('merchants-unpaid-transactions', 'Admins\MerchantPaymentController@unpaid')->name('admin.merchants_transactions.unpaid');

    Route::get('accounts/merchants', 'Admins\StaffController@index')->name('admin.merchant_accounts.index');
    Route::get('accounts/merchants/reset-password', 'Admins\StaffController@showPasswordResetForm')->name('admin.merchant_accounts.password.reset_form');
    Route::post('accounts/merchants/reset-password', 'Admins\StaffController@sendResetLinkEmail')->name('admin.merchant_accounts.password.send_link');

    //Route::get('reports/collections/daily', 'Admins\CollectionReport\DailyReportController@dailyReport')->name('admin.collection_reports.daily');
    //Route::get('reports/collections/merchants', 'Admins\CollectionReport\MerchantReportController@merchantReport')->name('admin.collection_reports.merchants');
    //Route::get('reports/collections/buses', 'Admins\CollectionReport\BusesReportController@busesReport')->name('admin.collection_reports.buses');
    //Route::get('reports/collections/bookings', 'Admins\CollectionReport\BookingReportController@bookingReport')->name('admin.collection_reports.bookings');

    Route::get('reports/disbursement/daily', 'Admins\DisbursementReport\DailyReportController@dailyReport')->name('admin.disbursement_reports.daily');
    Route::get('reports/disbursement/merchants', 'Admins\DisbursementReport\MerchantReportController@merchantReport')->name('admin.disbursement_reports.merchants');
    Route::get('reports/disbursement/buses', 'Admins\DisbursementReport\BusesReportController@busesReport')->name('admin.disbursement_reports.buses');
    Route::get('reports/disbursement/bookings', 'Admins\DisbursementReport\BookingReportController@bookingReport')->name('admin.disbursement_reports.bookings');

    //Route::get('reports/collections/daily', "Admins\ReportController@index");
    Route::get('reports/collections/daily', 'Admins\CollectionReport\C2BCollectionsReportController@byDate')->name('admin.collection_reports.daily');
    Route::get('reports/collections/merchants', 'Admins\CollectionReport\C2BCollectionsReportController@byMerchants')->name('admin.collection_reports.merchants');
    Route::get('reports/collections/buses', 'Admins\CollectionReport\C2BCollectionsReportController@byBuses')->name('admin.collection_reports.buses');
    Route::get('reports/collections/tickets', 'Admins\CollectionReport\C2BCollectionsReportController@ticketsCount')->name('admin.tickets_count.daily');

    Route::post('monitor/server', 'Admins\MonitorSystemController@pingServerIp')->name('admin.monitor.ping');

});

Route::prefix('merchant')->group(function () {
    Route::get('/', 'Merchants\MerchantController@homepage')->name('merchant.home');

    // Authentication Routes...
    Route::get('login', 'Merchants\Auth\LoginController@showLoginForm')->name('merchant.login');
    Route::post('login', 'Merchants\Auth\LoginController@login')->name('merchant.login');
    Route::get('logout', 'Merchants\Auth\LoginController@logout')->name('merchant.logout');

    // Password Reset Routes...
    Route::get('password/change', 'Merchants\Auth\ChangePasswordController@showForm')->name('merchant.password.change');
    Route::post('password/change', 'Merchants\Auth\ChangePasswordController@changePassword')->name('merchant.password.update');
    Route::get('password/reset', 'Merchants\Auth\ForgotPasswordController@showLinkRequestForm')->name('merchant.password.request');
    Route::post('password/email', 'Merchants\Auth\ForgotPasswordController@sendResetLinkEmail')->name('merchant.password.email');
    Route::get('password/reset/{token}', 'Merchants\Auth\ResetPasswordController@showResetForm')->name('merchant.password.reset');
    Route::post('password/reset', 'Merchants\Auth\ResetPasswordController@reset')->name('merchant.password.reset.change');

    //Profile management routes
    Route::get('profile/show', 'Merchants\ProfileController@show')->name('merchant.profile.show');
    Route::get('profile/edit', 'Merchants\ProfileController@edit')->name('merchant.profile.edit');
    Route::put('profile/update', 'Merchants\ProfileController@update')->name('merchant.profile.update');

    //Staff management routes
    Route::get('staff', 'Merchants\StaffController@index')->name('merchant.staff.index');
    Route::get('staff/create', 'Merchants\StaffController@create')->name('merchant.staff.create');
    Route::post('staff/store', 'Merchants\StaffController@store')->name('merchant.staff.store');
    Route::get('staff/{id}/show', 'Merchants\StaffController@show')->name('merchant.staff.show');
    Route::get('staff/{id}/delete', 'Merchants\StaffController@delete')->name('merchant.staff.delete');
    Route::delete('staff/{id}', 'Merchants\StaffController@remove')->name('merchant.staff.remove');

    //Buses CRUD
    Route::get('buses', 'Merchants\BusController@index')->name('merchant.buses.index');
    Route::get('buses/{id}/edit', 'Merchants\BusController@edit')->name('merchant.buses.edit');
    Route::put('buses/{id}/edit', 'Merchants\BusController@update')->name('merchant.buses.update');
    Route::get('buses/{id}/show', 'Merchants\BusController@show')->name('merchant.buses.show');
    Route::get('buses/{id}/routes/assign', 'Merchants\Buses\BusRoutesController@assignRoute')->name('merchant.buses.assign_routes');
    Route::post('buses/{id}/routes/assign', 'Merchants\Buses\BusRoutesController@saveBusRoute')->name('merchant.buses.assign_routes');
    Route::post('buses/{id}/routes/trip/{t_id}/update/time', 'Merchants\Buses\BusRoutesController@updateTripTime');
    Route::get('buses/{id}/price/assign', 'Merchants\Buses\TripPriceController@assignPrice')->name('merchant.buses.assign_price');
    Route::post('buses/{id}/price/trip/{t_id}/save', 'Merchants\Buses\TripPriceController@saveTripPrice')->name('merchant.buses.save_price');
    Route::get('buses/{id}/routes/assign/{r_id}/locations', 'Merchants\Buses\BusRoutesController@getRouteLocations')->name('merchant.buses.routes.locations');
    Route::get('buses/{id}/schedules', 'Merchants\Buses\BusSchedulingController@create')->name('merchant.buses.schedules');
    Route::get('buses/{id}/schedules/create', 'Merchants\Buses\BusSchedulingController@create')->name('merchant.buses.schedules.create');
    Route::post('buses/{id}/schedules/create', 'Merchants\Buses\BusSchedulingController@store')->name('merchant.buses.schedules.store');
    Route::get('buses/{id}/schedules/events', 'Merchants\Buses\BusSchedulingController@busSchedules');
    Route::get('buses/{id}/schedule/assign', 'Merchants\ScheduleController@assignSchedule');

    //Schedules
    Route::get('schedules', 'Merchants\ScheduleController@index')->name('merchant.schedules.index');
    Route::get('schedules/{id}/remove', 'Merchants\ScheduleController@index')->name('merchant.schedules.remove');
    Route::get('schedules/create', 'Merchants\ScheduleController@create')->name('merchant.schedules.create');

    //Bookings
    Route::get('bookings', 'Merchants\BookingController@index')->name('merchant.bookings.index');

    //Trips
    //Route::get('trips', 'Merchants\TripController@index')->name('merchant.trips.index');

    //Tickets
    Route::get('tickets', 'Merchants\TicketController@index')->name('merchant.tickets.index');

    Route::get('onboarding-form', 'Merchants\OnboardingController@displayForm')->name('merchant.onboarding.form');
    Route::get('onboarding/ticket-info', 'Merchants\OnboardingController@getTicketInformation')->name('merchant.onboarding.ticket_info');
    Route::post('onboarding/confirm', 'Merchants\OnboardingController@confirmBoarded')->name('merchant.onboarding.confirm');

    //Bus routes routes CRUD
    Route::get('routes', 'Merchants\RouteController@index')->name('merchant.routes.index');
    //Collection transactions routes
    Route::get('collection/transactions/all', 'Merchants\CollectionTransactionsController@all')->name('merchant.collection.transactions.all');
    Route::get('collection/transactions/mpesa', 'Merchants\CollectionTransactionsController@mpesa')->name('merchant.collection.transactions.mpesa');
    Route::get('collection/transactions/tigopesa', 'Merchants\CollectionTransactionsController@tigopesa')->name('merchant.collection.transactions.tigopesa');

    Route::get('disbursement/transactions/all', 'Merchants\DisbursementTransactionsController@all')->name('merchant.disbursement.transactions.all');
    Route::get('disbursement/transactions/mpesa', 'Merchants\DisbursementTransactionsController@mpesa')->name('merchant.disbursement.transactions.mpesa');
    Route::get('disbursement/transactions/tigopesa', 'Merchants\DisbursementTransactionsController@tigopesa')->name('merchant.disbursement.transactions.tigopesa');

});
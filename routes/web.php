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
Route::get('/', 'Users\UserController@homepage')->name('user.home');
Route::get('/about-us', 'Users\UserController@aboutUs')->name('user.about_us');
Route::get('/contact-us', 'Users\UserController@contactUs')->name('user.contact_us');
Route::get('/testimonials', 'Users\UserController@testimonials')->name('user.testimonials');
// Authentication Routes...
Route::get('login', 'Users\Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Users\Auth\LoginController@login')->name('login');
Route::get('logout', 'Users\Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::get('register', 'Users\Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Users\Auth\RegisterController@register')->name('register.submit');

// Password Reset Routes...
Route::get('password/change', 'Users\Auth\ChangePasswordController@showForm')->name('password.change');
Route::post('password/change', 'Users\Auth\ChangePasswordController@changePassword')->name('password.update');
Route::get('password/reset', 'Users\Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Users\Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Users\Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Users\Auth\ResetPasswordController@reset')->name('password.reset');

Route::get('booking/select/trip', 'Users\BookingController@selectTimetable')->name('booking.select.timetable');
Route::get('booking/select/bus', 'Users\BookingController@selectBus')->name('booking.select.bus');
Route::get('booking/schedules/{id}/sub-route/{s_id}', 'Users\BookingController@selectSeat')->name('booking.select.seats');
Route::get('booking/schedules/{id}/sub-route/{s_id}/user-info', 'Users\BookingController@prepareBooking')->name('booking.details.prepare');
Route::post('booking/details/{id}/sub-route/{s_id}/store', 'Users\BookingController@storeBookingDetails')->name('booking.details.store');

Route::prefix('user')->group(function () {
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
    Route::post('password/reset', 'Admins\Auth\ResetPasswordController@reset')->name('admin.password.reset');
//Profile management routes
    Route::get('profile/view', 'Admins\ProfileController@show')->name('admin.profile.show');
    Route::get('profile/edit', 'Admins\ProfileController@edit')->name('admin.profile.edit');
    Route::put('profile/update/{id}', 'Admins\ProfileController@update')->name('admin.profile.update');

    //Merchant management routes
    Route::get('merchants', 'Admins\MerchantController@index')->name('admin.merchant.index');
    Route::get('merchants/create', 'Admins\MerchantController@create')->name('admin.merchant.create');
    Route::post('merchants/store', 'Admins\MerchantController@store')->name('admin.merchant.store');
    Route::get('merchants/{id}/show', 'Admins\MerchantController@show')->name('admin.merchant.show');
    Route::get('merchants/{id}/delete', 'Admins\MerchantController@delete')->name('admin.merchant.delete');
    Route::delete('merchants/{id}', 'Admins\MerchantController@remove')->name('admin.merchant.remove');
    //Location CRUD route
    Route::get('locations', 'Admins\LocationController@index')->name('admin.location.index');
    Route::get('locations/create', 'Admins\LocationController@create')->name('admin.location.create');
    Route::post('locations/store', 'Admins\LocationController@store')->name('admin.location.store');
    Route::get('locations/{id}/show', 'Admins\LocationController@show')->name('admin.location.show');
    Route::get('locations/{id}/delete', 'Admins\LocationController@delete')->name('admin.location.delete');
    Route::delete('locations/{id}', 'Admins\LocationController@remove')->name('admin.location.remove');
    //Bus routes routes CRUD
    Route::get('routes', 'Admins\RouteController@index')->name('admin.route.index');
    Route::get('routes/create', 'Admins\RouteController@create')->name('admin.route.create');
    Route::post('routes/store', 'Admins\RouteController@store')->name('admin.route.store');
    Route::get('routes/{id}/show', 'Admins\RouteController@show')->name('admin.route.show');
    Route::get('routes/{id}/edit', 'Admins\RouteController@edit')->name('admin.route.edit');
    Route::put('routes/{id}', 'Admins\RouteController@edit')->name('admin.route.update');
    Route::get('routes/{id}/delete', 'Admins\RouteController@delete')->name('admin.route.delete');
    Route::delete('routes/{id}', 'Admins\RouteController@remove')->name('admin.route.remove');
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
    Route::get('buses/{id}/show', 'Admins\BusController@show')->name('admin.buses.show');
    Route::get('buses/{id}/delete', 'Admins\BusController@delete')->name('admin.buses.delete');
    Route::delete('buses/{id}', 'Admins\BusController@remove')->name('admin.buses.remove');
    //Merchant schedule routes
    Route::get('schedules', 'Admins\ScheduleController@index')->name('admin.schedules.index');
    Route::get('operation-day', 'Admins\ScheduleController@index')->name('admin.bus-route.approve');
    //Bookings
    Route::get('bookings', 'Admins\BookingController@index')->name('admin.bookings.index');
    //Inactive routes
    Route::get('inactive/bus-route', 'Admins\ApproveRouteController@showBusRoutes')->name('admin.bus-route.inactive.show');
    Route::get('bus-route', 'Admins\BusRouteController@index')->name('admin.bus-routes.index');
    Route::get('approvals/inactive/bus-route/{id}/approve', 'Admins\ApproveRouteController@approveBusRoute')->name('admin.bus-route.approve');
    Route::get('approvals/inactive/bus-route/{id}/confirm', 'Admins\ApproveRouteController@approveConfirm')->name('admin.bus-route.approve.confirm');
    Route::post('approvals/inactive/bus-route/{id}/authorize', 'Admins\ApproveRouteController@authorizeBusRoute')->name('admin.bus-route.authorize');
    //Admin approvals
    Route::get('approvals', 'Admins\ApprovalsController@index')->name('admin.approvals.index');
    Route::get('approvals/bus-routes', 'Admins\ApprovalsController@busRoutes')->name('admin.approvals.bus-routes');
    Route::get('approvals/reassign-schedule', 'Admins\ApprovalsController@reassignedSchedules')->name('admin.approvals.reassigned-schedules');
    Route::get('approvals/reassign-schedule/{id}/show', 'Admins\ApprovalsController@showReassignedSchedule')->name('admin.approvals.reassigned-schedules.show');
    Route::get('approvals/reassign-schedule/{id}/bookings', 'Admins\ApprovalsController@showBookings')->name('admin.approvals.reassigned-schedules.bookings');
    Route::post('approvals/reassign-schedule/{id}/confirm', 'Admins\ApprovalsController@confirm')->name('admin.approvals.reassigned-schedules.confirm');

    Route::get('ticket_prices', 'Admins\TicketPriceController@index')->name('admin.ticket_prices.index');
    Route::get('ticket_prices/create', 'Admins\TicketPriceController@create')->name('admin.ticket_prices.create');
    Route::get('ticket_prices/{id}/edit', 'Admins\TicketPriceController@edit')->name('admin.ticket_prices.edit');
    Route::post('ticket_prices/{id}/edit', 'Admins\TicketPriceController@update')->name('admin.ticket_prices.update');
    Route::post('ticket_prices/create', 'Admins\TicketPriceController@store')->name('admin.ticket_prices.store');
    Route::get('ticket_prices/{id)/delete', 'Admins\TicketPriceController@remove')->name('admin.ticket_prices.delete');
    Route::delete('ticket_prices/{id)/remove', 'Admins\TicketPriceController@remove')->name('admin.ticket_prices.remove');
    Route::get('trips', 'Admins\SubRouteController@index')->name('admin.sub_routes.index');


    //Admin account CRUD routes (V2.0)
    Route::get('accounts/admins', 'Admins\AdminAccountsController@index')->name('admin.admin_accounts.index');
    Route::get('accounts/admins/create', 'Admins\AdminAccountsController@create')->name('admin.admin_accounts.create');
    Route::post('accounts/admins/create', 'Admins\AdminAccountsController@store')->name('admin.admin_accounts.store');
    Route::delete('accounts/admins/{id}/delete', 'Admins\AdminAccountsController@destroy')->name('admin.admin_accounts.destroy');

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
    Route::post('password/reset', 'Merchants\Auth\ResetPasswordController@reset')->name('merchant.password.reset');
//Profile management routes
    Route::get('profile/view', 'Merchants\ProfileController@show')->name('merchant.profile.show');
    Route::get('profile/edit', 'Merchants\ProfileController@edit')->name('merchant.profile.edit');
    Route::put('profile/update/{id}', 'Merchants\ProfileController@update')->name('merchant.profile.update');
//Buses management routes
    Route::get('buses', 'Merchants\BusController@index')->name('merchant.buses.index');
    Route::get('buses/create', 'Merchants\BusController@create')->name('merchant.buses.create');
    Route::post('buses/store', 'Merchants\BusController@store')->name('merchant.buses.store');
    Route::get('buses/{id}/show', 'Merchants\BusController@show')->name('merchant.buses.show');
    Route::get('buses/{id}/delete', 'Merchants\BusController@delete')->name('merchant.buses.delete');
    Route::delete('buses/{id}', 'Merchants\BusController@remove')->name('merchant.buses.remove');
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
    Route::post('buses/{id}/edit', 'Merchants\BusController@update')->name('merchant.buses.update');
    Route::delete('buses/{id}', 'Merchants\BusController@remove')->name('merchant.buses.remove');
    //Out of service routes
    Route::get('buses/{id}/out-of-service', 'Merchants\OutOfServiceController@index')->name('merchant.buses.oos.index');
    Route::get('buses/{id}/out-of-service/confirm', 'Merchants\OutOfServiceController@confirm')->name('merchant.buses.oos.confirm');
    Route::post('buses/{id}/out-of-service/change', 'Merchants\OutOfServiceController@change')->name('merchant.buses.oos.change');
    Route::get('buses/{id}/out-of-service/{scheduleId}/reassign', 'Merchants\ReassignBusController@showReplacement')->name('merchant.buses.oos.reassign');
    Route::get('buses/{id}/out-of-service/{scheduleId}/reassign/select', 'Merchants\ReassignBusController@confirmReassign')->name('merchant.buses.oos.reassign.select');
    Route::post('buses/{id}/out-of-service/{scheduleId}/reassign/confirm', 'Merchants\ReassignBusController@reassign')->name('merchant.buses.oos.reassign.confirm');

    //Seats routes
    Route::get('buses/{id}/seats', 'Merchants\SeatController@index')->name('merchant.bus.seats');
    Route::get('buses/{id}/seats/create', 'Merchants\SeatController@create')->name('merchant.bus.seats.create');

    //Timetables route
    Route::get('timetables', 'Merchants\TimetableController@index')->name('merchant.timetable.index');
    Route::get('seats', 'Merchants\TimetableController@seatView');
    //Ticket prices routes
    Route::get('buses/{id}/ticket-prices', 'Merchants\TicketPriceController@index')->name('merchant.ticket_price.index');
    Route::get('ticket-prices/{id}/create', 'Merchants\TicketPriceController@create')->name('merchant.ticket_price.create');
    Route::post('ticket-prices/{id}/create', 'Merchants\TicketPriceController@store')->name('merchant.ticket_price.store');
    Route::get('ticket-prices/{id}/edit', 'Merchants\TicketPriceController@edit')->name('merchant.ticket_price.edit');
    Route::post('ticket-prices/{id}/edit', 'Merchants\TicketPriceController@update')->name('merchant.ticket_price.update');
    Route::delete('buses/{bus_id}/ticket-prices/{id}/delete', 'Merchants\TicketPriceController@delete')->name('merchant.ticket_price.delete');

    //Merchant schedule routes
    Route::get('schedules', 'Merchants\ScheduleController@index')->name('merchant.schedules.index');
    Route::get('buses/{id}/schedules', 'Merchants\ScheduleController@busSchedules')->name('merchant.bus.schedules');
    //Bookings
    Route::get('bookings', 'Merchants\BookingController@index')->name('merchant.bookings.index');
});
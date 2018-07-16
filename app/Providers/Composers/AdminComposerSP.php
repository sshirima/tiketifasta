<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 5/28/2018
 * Time: 7:13 PM
 */

namespace App\Providers\Composers;

use App\Http\ViewComposers\AdminComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AdminComposerSP extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        // Using class based composers...
        View::composer(
            [
                'admins.pages.accounts.index',
                'admins.pages.accounts.create',
                'admins.pages.buses.index',
                'admins.pages.buses.create',
                'admins.pages.buses.show',
                'admins.pages.bus_route.show',
                'admins.pages.buses.authorize',
                'admins.pages.bus_schedules.index',
                'admins.pages.merchants.authorize',
                'admins.pages.merchants.edit',
                'admins.pages.schedules.index',
                'admins.pages.bookings.index',
                'admins.pages.payment_accounts.index',
                'admins.pages.payment_accounts.create',
            ],
            AdminComposer::class
        );
    }
}
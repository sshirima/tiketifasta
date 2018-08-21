<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 5/28/2018
 * Time: 7:13 PM
 */

namespace App\Providers\Composers;

use App\Http\ViewComposers\AdminComposer;
use App\Http\ViewComposers\MerchantComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class MerchantComposerSP extends ServiceProvider
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
                'merchants.pages.buses.index',
                'merchants.pages.buses.edit',
                'merchants.pages.buses.show',
                'merchants.pages.bus_routes.assign',
                'merchants.pages.bus_route_price.assign',
                'merchants.pages.schedules.index',
                'merchants.pages.schedules.create',
                'merchants.pages.bookings.index',
                'merchants.pages.trips.index',
                'merchants.pages.tickets.index',
                'merchants.pages.profile.show',
                'merchants.pages.profile.edit',
                'merchants.pages.auth.changepass',
            ],
            MerchantComposer::class
        );
    }
}
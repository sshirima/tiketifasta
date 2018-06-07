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
            ],
            AdminComposer::class
        );
    }
}
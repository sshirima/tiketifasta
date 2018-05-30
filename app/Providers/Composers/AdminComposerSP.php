<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 5/28/2018
 * Time: 7:13 PM
 */

namespace App\Providers\Composers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AdminComposer extends ServiceProvider
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
            ['admins.pages.dashboard',
            ],
            AdminCom::class
        );
    }
}
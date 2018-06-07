<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 5/28/2018
 * Time: 7:16 PM
 */

namespace App\Http\ViewComposers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MerchantComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        if(Auth::guard('merchant')->check()){
            $view->with('merchant', auth()->user());
        }
    }

}
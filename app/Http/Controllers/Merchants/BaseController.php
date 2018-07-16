<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/12/2018
 * Time: 12:57 AM
 */

namespace App\Http\Controllers\Merchants;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BaseController extends Controller
{

    protected $merchantId;

    public function __construct()
    {
        $this->middleware('auth:merchant');
    }

    protected function setMerchantId(): void
    {
        $this->merchantId = auth()->user()->merchant_id;
    }


}
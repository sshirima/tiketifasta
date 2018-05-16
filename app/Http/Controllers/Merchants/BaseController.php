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
    const PARAM_USER_MERCHANT = 'merchant';
    const PARAM_ERROR_MESSAGE = 'message';

    public $viewData = array();
    public $viewErrorData = array();

    public function __construct()
    {
        $this->middleware('auth:merchant');
    }

    public function getDefaultViewData(): void
    {
        $this->viewData[self::PARAM_USER_MERCHANT] = auth()->user();
    }

    public function getDefaultViewErrorData($message): void
    {
        $this->viewErrorData[self::PARAM_ERROR_MESSAGE] = $message;
    }

}
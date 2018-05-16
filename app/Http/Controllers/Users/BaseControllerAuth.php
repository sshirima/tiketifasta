<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/12/2018
 * Time: 1:15 AM
 */

namespace App\Http\Controllers\Users;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BaseControllerAuth extends Controller
{
    const PARAM_USER_ADMIN = 'user';
    const PARAM_ERROR_MESSAGE = 'message';

    public $viewData = array();
    public $viewErrorData = array();

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getDefaultViewData(): void
    {
        $this->viewData[self::PARAM_USER_ADMIN] = auth()->user();
    }

    public function getDefaultViewErrorData($message): void
    {
        $this->viewErrorData[self::PARAM_ERROR_MESSAGE] = $message;
    }

}
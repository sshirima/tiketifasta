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

class BaseController extends Controller
{
    const PARAM_ERROR_MESSAGE = 'message';

    public $viewData = array();
    public $viewErrorData = array();

    public function getDefaultViewErrorData($message): void
    {
        $this->viewErrorData[self::PARAM_ERROR_MESSAGE] = $message;
    }

}
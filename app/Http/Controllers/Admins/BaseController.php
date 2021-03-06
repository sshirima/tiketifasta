<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/12/2018
 * Time: 12:57 AM
 */

namespace App\Http\Controllers\Admins;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laracasts\Flash\Flash;

class BaseController extends Controller
{
    const PARAM_USER_ADMIN = 'admin';
    const PARAM_ERROR_MESSAGE = 'message';

    public $viewData = array();
    public $viewErrorData = array();

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function getDefaultViewData(): void
    {
        $this->viewData[self::PARAM_USER_ADMIN] = auth()->user();
    }

    public function getDefaultViewErrorData($message): void
    {
        $this->viewErrorData[self::PARAM_ERROR_MESSAGE] = $message;
    }

    /**
     * @param $model
     * @param $successMsg
     * @param $errorMessage
     */
    public function createFlashResponse($model,$successMsg,$errorMessage): void
    {
        if (empty($model)) {
            if(isset($errorMessage)){
                Flash::error($errorMessage);
            }
        } else{
            if(isset($successMsg)){
                Flash::success($successMsg);
            }
        }
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/12/2018
 * Time: 5:51 PM
 */

namespace App\Http\Requests\Merchant;


use App\Models\Bus;
use App\Models\Merchant;
use App\Models\Staff;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateBusRequest extends FormRequest
{

    const REG_NUMBER = Bus::COLUMN_REG_NUMBER;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard('merchant')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return[
            Bus::COLUMN_CONDUCTOR_NAME => 'required',
            Bus::COLUMN_DRIVER_NAME => 'required',
            Bus::COLUMN_BUS_CONDITION => 'required',
        ];
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/12/2018
 * Time: 5:51 PM
 */

namespace App\Http\Requests\Admin\Buses;


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
        return Auth::guard('admin')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return[
            Bus::COLUMN_OPERATION_START => 'required|date',
            Bus::COLUMN_OPERATION_END => 'required|date',
        ];
    }

}
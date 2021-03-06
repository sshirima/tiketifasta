<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/12/2018
 * Time: 5:51 PM
 */

namespace App\Http\Requests\Admin;


use App\Models\Bus;
use App\Models\Merchant;
use App\Models\Staff;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateBusRequest extends FormRequest
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
            Bus::COLUMN_REG_NUMBER => 'required|max:255|unique:buses',
            Bus::COLUMN_BUSTYPE_ID => 'required|numeric|min:1',
            Bus::COLUMN_MERCHANT_ID => 'required|numeric|min:1',
            'bus_images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ];
    }

}
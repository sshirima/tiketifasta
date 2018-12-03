<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/12/2018
 * Time: 5:51 PM
 */

namespace App\Http\Requests\Admin;

use App\Models\Location;
use App\Models\Route;
use Illuminate\Foundation\Http\FormRequest;

class CreateMerchantPayAccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'merchant_id' => 'required|not_in:0',
            'account_number' => 'required|max:12',
            'payment_mode' => 'required|not_in:0',
        ];
    }

}
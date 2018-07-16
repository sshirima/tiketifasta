<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/12/2018
 * Time: 5:51 PM
 */

namespace App\Http\Requests\Merchant;


use App\Models\Merchant;
use Illuminate\Foundation\Http\FormRequest;

class CreateMerchantRequest extends FormRequest
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
            'name' => 'required|max:255|unique:merchants',
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'phonenumber' => 'required|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'account_number'=>'required|integer',
            'payment_mode'=>'required|in:mpesa,tigopesa,airtelmoney',
        ];
    }

}
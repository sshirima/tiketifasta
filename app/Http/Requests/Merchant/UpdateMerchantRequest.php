<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/11/2018
 * Time: 8:48 PM
 */

namespace App\Http\Requests\Merchant;


use App\Models\Merchant;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMerchantRequest extends FormRequest
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
        return Merchant::$rules;
    }

}
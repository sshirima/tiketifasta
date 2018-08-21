<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/12/2018
 * Time: 5:51 PM
 */

namespace App\Http\Requests\Merchant;


use App\Models\Merchant;
use App\Models\Staff;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMerchantProfileRequest extends FormRequest
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
            Staff::COLUMN_FIRST_NAME => 'required|max:255',
            Staff::COLUMN_LAST_NAME => 'required|max:255',
        ];
    }

}
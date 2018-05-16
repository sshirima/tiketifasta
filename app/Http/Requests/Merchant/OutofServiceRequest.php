<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 4/15/2018
 * Time: 2:20 PM
 */

namespace App\Http\Requests\Merchant;


use Illuminate\Foundation\Http\FormRequest;

class OutofServiceRequest extends FormRequest
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
            'details'=>'required'
        ];
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/25/2018
 * Time: 4:37 PM
 */

namespace App\Http\Requests\Users;


use Illuminate\Foundation\Http\FormRequest;

class CreateBookingRequest extends FormRequest
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
            'title'=>'required',
            'firstname'=>'required',
            'lastname'=>'required',
            'phonenumber'=>'required',
            'payment'=>'required',
            'agree_terms'=>'required',
            'seat'=>'required',
        ];
    }

}
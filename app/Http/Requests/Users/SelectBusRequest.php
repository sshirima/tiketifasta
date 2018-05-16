<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/25/2018
 * Time: 4:24 PM
 */

namespace App\Http\Requests\Users;


use Illuminate\Foundation\Http\FormRequest;

class SelectBusRequest extends FormRequest
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
            'start_location'=>'required',
            'destination'=>'required',
            'departing_date'=>'required',
            'returning_date'=>'required_if:trip_type,round_trip',
        ];
    }

}
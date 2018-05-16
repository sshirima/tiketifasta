<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 4/19/2018
 * Time: 10:32 PM
 */

namespace App\Http\Requests\Merchant;


use Illuminate\Foundation\Http\FormRequest;

class ReassignBusRequest extends FormRequest
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
            'reassigned_bus_id'=>'required',
            'old_schedule_id'=>'required',
            'reassign_comment'=>'required',
        ];
    }


}
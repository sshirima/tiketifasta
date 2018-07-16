<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 7/7/2018
 * Time: 6:29 PM
 */

namespace App\Http\Requests\Merchant;


use Illuminate\Foundation\Http\FormRequest;

class AssignScheduleRequest extends FormRequest
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
            'dates.*' => 'required',
            'direction' => 'required',
            'status' => 'required',
        ];
    }

}
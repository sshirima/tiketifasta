<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/12/2018
 * Time: 5:51 PM
 */

namespace App\Http\Requests\Admin;


use App\Models\Bus;
use App\Models\Bustype;
use App\Models\Merchant;
use App\Models\Staff;
use Illuminate\Foundation\Http\FormRequest;

class CreateBusTypeRequest extends FormRequest
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
        return Bustype::$rules;
    }

}
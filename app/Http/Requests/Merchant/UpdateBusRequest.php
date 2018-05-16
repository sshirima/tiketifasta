<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/11/2018
 * Time: 8:48 PM
 */

namespace App\Http\Requests\Merchant;


use App\Models\Bus;
use App\Models\Merchant;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBusRequest extends FormRequest
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
            'route_id' => 'required|numeric|min:1',
            'source.*' => 'required|min:1',
            'trip_dates' => 'required',
            'destination.*' => 'required|min:1',
            'travelling_days.*' => 'required|min:1',
            'depart_time.*' => 'required_with:arrival_time.*|date_format:"H:i"',
            'arrival_time.*' => 'required_with:depart_time.*|date_format:"H:i"',
        ];
    }

}
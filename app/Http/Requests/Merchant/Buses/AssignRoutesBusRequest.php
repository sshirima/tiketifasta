<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/12/2018
 * Time: 5:51 PM
 */

namespace App\Http\Requests\Merchant\Buses;


use App\Models\Bus;
use App\Models\Merchant;
use App\Models\Staff;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AssignRoutesBusRequest extends FormRequest
{

    const REG_NUMBER = Bus::COLUMN_REG_NUMBER;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard('merchant')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return[
            Bus::COLUMN_ROUTE_ID => 'required|numeric|min:1',
            'trips.*.source' => 'required|min:1',
            'trips.*.destination' => 'required|min:1',
            'trips.*.travelling_days' => 'required|min:1',
            'trips.*.depart_time' => 'required|date_format:"h:i A"',
            'trips.*.arrival_time' => 'required|date_format:"h:i A"',
        ];
    }

}
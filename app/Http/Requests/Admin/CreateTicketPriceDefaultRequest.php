<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/12/2018
 * Time: 5:51 PM
 */

namespace App\Http\Requests\Admin;

use App\Models\Location;
use App\Models\Route;
use App\Models\TicketPriceDefault;
use Illuminate\Foundation\Http\FormRequest;

class CreateTicketPriceDefaultRequest extends FormRequest
{
    const INPUT_DESTINATIONS = 'destinations';
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
        return TicketPriceDefault::$rules;
    }

}
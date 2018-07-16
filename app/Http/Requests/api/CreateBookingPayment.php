<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/11/2018
 * Time: 8:48 PM
 */

namespace App\Http\Requests\api;


use App\Models\Admin;
use App\Models\BookingPayment;
use Illuminate\Foundation\Http\FormRequest;

class CreateBookingPayment extends FormRequest
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
            BookingPayment::COLUMN_AMOUNT=>'required',
            BookingPayment::COLUMN_PAYMENT_REF=>'required',
            BookingPayment::COLUMN_PHONE_NUMBER=>'required',
        ];
    }

}
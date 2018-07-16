<?php

namespace App\Http\Resources\Payments;

use App\Models\BookingPayment;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingPaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            BookingPayment::COLUMN_PHONE_NUMBER=>$this->phone_number,
            BookingPayment::COLUMN_AMOUNT=>$this->amount
        ];
    }
}

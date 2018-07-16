<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 7/8/2018
 * Time: 1:27 PM
 */

namespace App\Services\Bookings;


use App\Models\Bus;
use App\Models\Day;
use App\Models\Merchant;

trait RetrieveBookingsService
{
    protected $dayId;
    protected $busId;

    public function getDailyBooking($dayId){
        return Day::with(['bookings'])->find($dayId);
    }

    public function getBusBooking($busId){
        return Bus::with(['bookings'])->find($busId);
    }

    public function getMerchantBookings($merchantId){
        return Merchant::with(['schedules','schedules.bookings','schedules.bookings.trip','schedules.bookings.trip.from',
            'schedules.bookings.trip.to'])->find($merchantId);
    }

    public function getMerchantDailyBooking($dayId, $merchantId){
        $this->dayId = $dayId;
        return Merchant::with(['schedules'=>function($query){$query->where(['day_id'=>$this->dayId]);},'schedules.bookings'])->find($merchantId);

    }

    public function getMerchantBusBooking($busId, $merchantId){
        $this->busId = $busId;
        return Merchant::with(['schedules'=>function($query){$query->where(['bus_id'=>$this->busId]);},'schedules.bookings'])->find($merchantId);
    }

    public function getMerchantDailyBusBooking($busId, $dayId, $merchantId){
        $this->busId = $busId;
        $this->dayId = $dayId;
        return Merchant::with(['schedules'=>function($query){$query->where(['bus_id'=>$this->busId,'day_id'=>$this->dayId]);},'schedules.bookings'])->find($merchantId);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 6/27/2018
 * Time: 10:44 PM
 */

namespace App\Http\Controllers\Users\Bookings;


use App\Http\Controllers\Users\BaseController;
use App\Models\Day;
use App\Models\Trip;
use App\Services\DateTimeService;
use Illuminate\Http\Request;

class SelectSeatController extends BaseController
{
    private $tripId;

    public function selectSeat(Request $request, $busId){
        $d = DateTimeService::convertDate($request->all()['date'],'Y-m-d' );

        $this->tripId = $tripId;

        $date =  Day::with(['schedules','schedules.bus','schedules.bus.merchant',
            'schedules.bus.trips'=>function($query){$query->where('id', '=', $this->tripId);},
            'schedules.bus.trips.from','schedules.bus.trips.to'])->where(['date'=>$d])->first();

        return $date;
    }

}
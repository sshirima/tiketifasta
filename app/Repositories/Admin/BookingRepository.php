<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/11/2018
 * Time: 7:21 PM
 */

namespace App\Repositories\Admin;

use App\Models\Booking;
use App\Models\Bus;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Okipa\LaravelBootstrapTableList\TableList;

class BookingRepository extends BaseRepository
{
    public $conditions = array();

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return Booking::class;
    }

    public function dailyBookings(Request $request){

        $this->getConditions($request);

        $table = $this->getDailyBookings();

        return $table;
    }

    /**
     * @param Request $request
     */
    public function getConditions(Request $request): void
    {
        if ($request->filled('date')) {
            $this->conditions['operation_days.date'] = $request['date'];
        } /*else {
            $now = new \DateTime();
            $this->conditions['operation_days.date'] = $now->format('Y-m-d');
        }*/

        if ($request->filled('bus_id')) {
            if ($request['bus_id'] > 0) {
                $this->conditions['buses.id'] = $request['bus_id'];
            }
        }

        if ($request->filled('status')) {
            if (!$request['status'] == 0) {
                $this->conditions['bookings.status'] = $request['status'];
            }
        }
    }

    /**
     * @return mixed
     */
    public function getDailyBookings()
    {
        $table = app(TableList::class)
            ->setModel(Booking::class)
            ->setRowsNumber(10)
            ->enableRowsNumberSelector()
            ->setRoutes([
                'index' => ['alias' => 'merchant.bookings.index', 'parameters' => []]
            ])->addQueryInstructions(function ($query) {
                $query->select(['bookings.firstname', 'bookings.lastname', 'operation_days.date', 'bus_route.start_time',
                    'timetables.arrival_time', 'B.name as location_start', 'A.name as location_end', 'routes.route_name',
                    'bookings.email', 'bookings.phonenumber','buses.reg_number', 'bookings.status'])
                    ->join('daily_timetables', 'daily_timetables.id', '=', 'bookings.daily_timetable_id')
                    ->join('operation_days', 'operation_days.id', '=', 'daily_timetables.operation_day_id')
                    ->join('bus_route', 'bus_route.id', '=', 'daily_timetables.bus_route_id')
                    ->join('routes', 'bus_route.route_id', '=', 'routes.id')
                    ->join('timetables', 'timetables.id', '=', 'daily_timetables.timetable_id')
                    ->join('buses', 'buses.id', '=', 'bus_route.bus_id')
                    ->join('locations as A', 'A.id', '=', 'timetables.location_id')
                    ->join('locations as B', 'B.id', '=', 'routes.start_location')
                    ->where($this->conditions);
            });
        return $table;
    }
}
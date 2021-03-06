<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Okipa\LaravelBootstrapTableList\TableList;

class Booking extends Model
{
    const STATUS_PENDING = 'PENDING';
    const STATUS_AUTHORIZED = 'AUTHORIZED';
    const STATUS_CANCELLED = 'CANCELLED';
    const STATUS_CONFIRMED = 'CONFIRMED';
    const STATUS_EXPIRED = 'EXPIRED';

    const COLUMN_ID = 'id';
    const COLUMN_TITLE = 'title';
    const COLUMN_FIRST_NAME = 'firstname';
    const COLUMN_LAST_NAME = 'lastname';
    const COLUMN_PHONE_NUMBER = 'phonenumber';
    const COLUMN_PAYMENT = 'payment';
    const COLUMN_EMAIL = 'email';
    const COLUMN_STATUS = 'status';
    const COLUMN_TRIP_ID = 'trip_id';
    const COLUMN_SCHEDULE_ID = 'schedule_id';
    const COLUMN_SEAT_ID = 'seat_id';
    const COLUMN_PRICE = 'price';
    const COLUMN_SUB_ROUTE_ID = 'sub_route_id';
    const COLUMN_CREATED_AT = 'created_at';
    const COLUMN_UPDATED_AT = 'updated_at';
    const COLUMN_PAYMENT_REF = 'payment_ref';
    const COLUMN_BOARDING_LOCATION = 'boarding_location';
    const COLUMN_DROPPING_LOCATION = 'dropping_location';

    const ID = self::TABLE.'.'.'id';
    const TITLE = self::TABLE.'.'.'title';
    const FIRST_NAME = self::TABLE.'.'.'firstname';
    const LAST_NAME = self::TABLE.'.'.'lastname';
    const PHONE_NUMBER = self::TABLE.'.'.'phonenumber';
    const PAYMENT = self::TABLE.'.'.'payment';
    const EMAIL = self::TABLE.'.'.'email';
    const STATUS = self::TABLE.'.'.self::COLUMN_STATUS;
    const PAYMENT_REF = self::TABLE.'.'.self::COLUMN_PAYMENT_REF;
    const SCHEDULE_ID = self::TABLE.'.'.'schedule_id';
    const SEAT_ID = self::TABLE.'.'.'seat_id';
    const SUB_ROUTE_ID = self::TABLE.'.'.'sub_route_id';
    const CREATED_TIME = self::TABLE.'.'.'created_at';
    const UPDATED_TIME = self::TABLE.'.'.'updated_at';

    const TABLE = 'bookings';

    public $conditions = array();

    protected $fillable = [
        self::COLUMN_TITLE,self::COLUMN_FIRST_NAME,self::COLUMN_LAST_NAME,self::COLUMN_PHONE_NUMBER,self::COLUMN_PAYMENT_REF
        ,self::COLUMN_PAYMENT,self::COLUMN_EMAIL,self::COLUMN_SCHEDULE_ID,self::COLUMN_SEAT_ID,self::COLUMN_TRIP_ID,
        self::COLUMN_PRICE,self::COLUMN_STATUS, self::COLUMN_BOARDING_LOCATION, self::COLUMN_DROPPING_LOCATION
    ];

    protected $table= self::TABLE;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function schedule(){
        return $this->belongsTo(Schedule::class,self::COLUMN_SCHEDULE_ID,Schedule::COLUMN_ID);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function seat(){
        return $this->belongsTo(Seat::class,self::COLUMN_SEAT_ID,Seat::COLUMN_ID);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     *
     */
    public function bookingPayment(){
        return $this->hasOne(BookingPayment::class,BookingPayment::COLUMN_BOOKING_ID,self::COLUMN_ID);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     *
     */
    public function ticket(){
        return $this->hasOne(Ticket::class,Ticket::COLUMN_BOOKING_ID,self::COLUMN_ID);
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function trip(){
        return $this->belongsTo(Trip::class,self::COLUMN_TRIP_ID,Trip::COLUMN_ID);
    }

    /**
     *
     */
    public function confirmBooking(){
        $this->status = self::STATUS_CONFIRMED;
        $this->update();
    }


    public function dailyBookings(Request $request){

        if ($request->filled('date')){
            $this->conditions['operation_days.date'] = $request['date'];
        } /*else {
            $now = new \DateTime();
            $this->conditions['operation_days.date'] = $now->format('Y-m-d');
        }*/

        if ($request->filled('bus_id')){
            if($request['bus_id'] > 0){
                $this->conditions['buses.id'] = $request['bus_id'];
            }
        }

        if ($request->filled('status')){
            if(! $request['status'] == 0){
                $this->conditions['bookings.status'] = $request['status'];
            }
        }

        $table= app(TableList::class)
            ->setModel(Booking::class)
            ->setRowsNumber(10)
            ->enableRowsNumberSelector()
            ->setRoutes([
                'index' => ['alias'=>'merchant.bookings.index','parameters' => []]
            ])->addQueryInstructions(function ($query) {
                $query->select(['bookings.firstname','bookings.lastname','operation_days.date','bus_route.start_time',
                    'timetables.arrival_time','B.name as location_start','A.name as location_end','routes.route_name','bookings.email','buses.reg_number','bookings.status'])
                    ->join('daily_timetables','daily_timetables.id','=','bookings.daily_timetable_id')
                    ->join('operation_days','operation_days.id','=','daily_timetables.operation_day_id')
                    ->join('bus_route','bus_route.id','=','daily_timetables.bus_route_id')
                    ->join('routes','bus_route.route_id','=','routes.id')
                    ->join('timetables','timetables.id','=','daily_timetables.timetable_id')
                    ->join('buses','buses.id','=','bus_route.bus_id')
                    ->join('locations as A','A.id','=','timetables.location_id')
                    ->join('locations as B','B.id','=','routes.start_location')
                    ->where($this->conditions);
            });

        $table->addColumn('reg_number')->setTitle('Bus#')->isSortable()->sortByDefault()->isSearchable()->setCustomTable('buses');

        $table->addColumn('date')->setTitle('Client')->isSortable()->setCustomTable('operation_days')
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity->firstname.' '.$entity->lastname.'</br>'.'('.$entity->email.')';
            });
        $table->addColumn('email')->setTitle('Date/Time')->isSortable()
            ->isCustomHtmlElement(function ($entity, $column) {
            return $entity->date.'</br>'.'('.$entity->start_time.'-'.$entity->arrival_time.')';
        });
        $table->addColumn()->setTitle('Route')->isSortable()
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity->route_name.'</br>'.'('.$entity->location_start.' to '.$entity->location_end.')';
            });
        $table->addColumn('status')->setTitle('Status')
            ->isCustomHtmlElement(function ($entity, $column) {
                if ($entity->status == Booking::STATUS_PENDING){
                    return "<span style='color: orange'>Pending <i class='fas fa-spinner'></i></span> ";
                } else if ($entity->status == Booking::STATUS_CONFIRMED){
                    return "<span style='color: green;'>Confirmed <i class='fas fa-check-circle'></i></span>";
                } else{
                    return "<span style='color: red;'>Cancelled <i class='fas fa-times-circle'></i></span>";
                }
        });

        return $table;
    }

}

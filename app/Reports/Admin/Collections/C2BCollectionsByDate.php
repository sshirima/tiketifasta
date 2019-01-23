<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 1/16/2019
 * Time: 6:03 PM
 */

namespace App\Reports\Admin\Collections;


use App\Models\BookingPayment;
use Illuminate\Support\Facades\DB;
use koolreport\processes\Limit;
use \koolreport\clients\Bootstrap; // Use Bootstrap service

class C2BCollectionsByDate extends \koolreport\KoolReport
{

    protected $conditions;

    public function settings()
    {
        if(env('APP_ENV') == 'production'){
            $conn ="mysql:host=".'172.31.2.175'.";dbname=".env('DB_DATABASE_PROD');
            $un = env('DB_USERNAME_PROD');
            $ps = env('DB_PASSWORD_PROD');
        } else {
            $conn = "mysql:host=".env('DB_HOST').";dbname=".env('DB_DATABASE');
            $un = env('DB_USERNAME');
            $ps = env('DB_PASSWORD');
        }
        return array(
            "dataSources"=>array(
                "database"=>array(
                    "connectionString"=>$conn,
                    "username"=>$un,
                    "password"=>$ps,
                    "charset"=>"utf8"
                )
            )
        );
    }
    protected function defaultParamValues()
    {
        return array(
            "transaction_status"=>BookingPayment::TRANS_STATUS_SETTLED,
        );
    }

    protected function bindParamsToInputs()
    {
        //$this->params['transaction_status'] = BookingPayment::TRANS_STATUS_SETTLED;
        return array(
            "transaction_status"=>"transaction_status",
        );
    }

    public function setup()
    {
        $query_params = array();
        if($this->params["transaction_status"]!=array())
        {
            $query_params[":transaction_status"] = $this->params["transaction_status"];
        }

        $query = DB::table('schedules')
            ->select(\DB::Raw('DATE(booking_payments.created_at) paid_date'),
                \DB::raw('sum(booking_payments.amount) as total'))
            ->join('bookings', 'bookings.schedule_id', '=', 'schedules.id')
            ->join('buses', 'buses.id', '=', 'schedules.bus_id')
            ->join('merchants', 'merchants.id', '=', 'buses.merchant_id')
            ->join('tickets', 'tickets.booking_id', '=', 'bookings.id')
            ->join('booking_payments', 'booking_payments.booking_id', '=', 'bookings.id')
            ->whereRaw('booking_payments.transaction_status',[$this->params["transaction_status"]])
            ->groupBy(\DB::raw("DATE(booking_payments.created_at)"))->toSql();

        $this->src('database')
            ->query($query)
            ->pipe(new Limit(array(10)))
            ->pipe($this->dataStore('c2b_collections_report'));
    }
}
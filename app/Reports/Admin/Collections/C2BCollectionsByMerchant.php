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

class C2BCollectionsByMerchant extends \koolreport\KoolReport
{

    protected $conditions;

    public function settings()
    {
        return array(
            "dataSources"=>array(
                "database"=>array(
                    "connectionString"=>"mysql:host=".env('DB_HOST', '127.0.0.1').";dbname=".env('DB_DATABASE', 'forge'),
                    "username"=>env('DB_USERNAME', 'forge'),
                    "password"=>env('DB_PASSWORD', ''),
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
            ->select(\DB::Raw('merchants.name as merchant_name'),
                \DB::raw('sum(booking_payments.amount) as total_amount'))
            ->join('bookings', 'bookings.schedule_id', '=', 'schedules.id')
            ->join('buses', 'buses.id', '=', 'schedules.bus_id')
            ->join('merchants', 'merchants.id', '=', 'buses.merchant_id')
            ->join('tickets', 'tickets.booking_id', '=', 'bookings.id')
            ->join('booking_payments', 'booking_payments.booking_id', '=', 'bookings.id')
            ->whereRaw('booking_payments.transaction_status',[$this->params["transaction_status"]])
            ->groupBy(\DB::raw("merchants.name"))->toSql();

        $this->src('database')
            ->query($query)
            ->pipe(new Limit(array(10)))
            ->pipe($this->dataStore('merchants_collections'));
    }
}
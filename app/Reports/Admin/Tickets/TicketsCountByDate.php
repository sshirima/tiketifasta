<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 1/16/2019
 * Time: 6:03 PM
 */

namespace App\Reports\Admin\Tickets;


use App\Models\BookingPayment;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use koolreport\processes\Limit;
use \koolreport\clients\Bootstrap; // Use Bootstrap service

class TicketsCountByDate extends \koolreport\KoolReport
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

    protected function bindParamsToInputs()
    {
        //$this->params['transaction_status'] = BookingPayment::TRANS_STATUS_SETTLED;
        return array(
            "status"=>"status",
        );
    }

    public function setup()
    {
        $query_params = array();
        if($this->params["status"]!=array())
        {
            $query_params[":status"] = $this->params["status"];
        }

        $query = DB::table('tickets')
            ->select(\DB::Raw('DATE(tickets.created_at) date'),
                \DB::raw('count(tickets.id) as total_tickets'),
                'tickets.status as status')
            ->groupBy(\DB::raw("DATE(tickets.created_at)"), 'tickets.status')->toSql();

        $this->src('database')
            ->query($query)
            ->pipe(new Limit(array(10)))
            ->pipe($this->dataStore('tickets_counts'))
            ->toJson();
    }
}
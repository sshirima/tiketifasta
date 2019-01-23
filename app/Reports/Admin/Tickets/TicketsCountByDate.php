<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 1/16/2019
 * Time: 6:03 PM
 */

namespace App\Reports\Admin\Tickets;

use Illuminate\Support\Facades\DB;
use koolreport\processes\Limit;

class TicketsCountByDate extends \koolreport\KoolReport
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
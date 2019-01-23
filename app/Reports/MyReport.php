<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 1/16/2019
 * Time: 6:03 PM
 */

namespace App\Reports;


use koolreport\processes\Group;
use koolreport\processes\Limit;
use koolreport\processes\Sort;
use \koolreport\clients\Bootstrap; // Use Bootstrap service

class MyReport extends \koolreport\KoolReport
{

    public function settings()
    {
        return array(
            "dataSources"=>array(
                "sales"=>array(
                    "connectionString"=>"mysql:host=".env('DB_HOST', '127.0.0.1').";dbname=".env('DB_DATABASE', 'forge'),
                    "username"=>env('DB_USERNAME', 'forge'),
                    "password"=>env('DB_PASSWORD', ''),
                    "charset"=>"utf8"
                )
            )
        );
    }
    public function setup()
    {
        $this->src('sales')
            ->query("SELECT phone_number,amount FROM tigoonline_c2b")
            ->pipe(new Limit(array(4)))
            ->pipe($this->dataStore('tigo_c2b_data'));
    }
}
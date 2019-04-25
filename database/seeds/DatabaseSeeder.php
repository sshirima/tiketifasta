<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
use \App\Domain\Admin\MonitorSystem\Services\RunCommandsHostServer;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //$this->call(MerchantsSeederTable::class);
        //$this->call(BusTypesSeederTable::class);
        //$this->call(RoutesSeederTable::class);
        //$this->call(BusesSeederTable::class);
        //$this->call(AssignBusRouteSeederTable::class);
        //$this->call(AssignScheduleSeederTable::class);
        //$this->call(BookingsSeederTable::class);
        //$this->call(MerchantPaymentSeederTable::class);
        //print json_encode($this->telnetIp('127.0.0.1', 443));
        /*$mon = new \App\Domain\Admin\MonitorSystem\MonitorSystemAggregate(new \App\Domain\Admin\MonitorSystem\MonitorSystemRepository());

        print  json_encode($mon->getListOfServersToMonitor());*/
        /*$ip = '127.0.0.1';
        print "Server $ip status>>".config('monitor_system.servers.127.0.0.1.status').PHP_EOL;*/
    }
}

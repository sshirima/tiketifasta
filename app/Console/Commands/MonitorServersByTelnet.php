<?php

namespace App\Console\Commands;

use App\Domain\Admin\MonitorSystem\MonitorSystemAggregate;
use App\Domain\NotifyUsers\NotifyUsersAggregate;
use App\Models\Day;
use App\Services\Schedules\SchedulesAuthorization;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MonitorServersByTelnet extends Command
{
    use SchedulesAuthorization;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitor-servers:telnet';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Monitor third party server by telnet command';
    protected $monitorSystemAggregate;
    protected $notifyUserAggregate;
    protected $servers;

    /**
     * MonitorServersByTelnet constructor.
     * @param MonitorSystemAggregate $monitorSystemAggregate
     * @param NotifyUsersAggregate $notifyUsersAggregate
     */
    public function __construct(MonitorSystemAggregate $monitorSystemAggregate, NotifyUsersAggregate $notifyUsersAggregate)
    {
        parent::__construct();
        $this->monitorSystemAggregate = $monitorSystemAggregate;
        $this->notifyUserAggregate = $notifyUsersAggregate;
    }

    /**
     * Disable expired schedules
     */
    public function handle()
    {
        Log::info("Running: $this->signature command". PHP_EOL);
        $this->servers = $this->monitorSystemAggregate->getListOfServersToMonitor();

        foreach ($this->servers as $key => $server) {
            //print json_encode($server);
            $output = $this->monitorSystemAggregate->telnetServer($server['ip_address'], $server['port_number']);

            if ($output['status'] == true) {
                //print $server['ip_address'] . ":" . $server['port_number'] . ">>Connected" . PHP_EOL;
            } else {
                Log::emergency($server['server_name'] . ">>Timeout" . ", output:" . json_encode($output) . PHP_EOL);
                //print $server['ip_address'] . ":" . $server['port_number'] . ">>Timeout" . ", output:" . json_encode($output) . PHP_EOL;
            }

            /*$a = false;//$output['status'];
            $b = 1;//$server['availability_status'];

            if ((!$a && $b) || ($a && !$b)) {

                print "Sending notifications: " . $server['ip_address'] . PHP_EOL;

                $this->monitorSystemAggregate->setServerAvailabilityStatus($server['ip_address'], $output['status']);

                $this->notifyViaSMS($server, $output['status']);
            }*/
        }
    }

    private function notifyViaSMS($server, $status){
        $configs = $this->monitorSystemAggregate->getSMSNotificationConfigs();

        print "Allow config:".json_encode($configs).PHP_EOL;

        if(!$configs['allowed']){
            Log::warning("SMS notification not allowed");
            print "SMS notification not allowed" . PHP_EOL;
            return false;
        }

        $st = $status == true?'reachable':'not reachable';
        $message = "Server ".$server['server_name']." is ". $st;
        $recipients = $configs['recipients'];

        foreach ($recipients as $key=>$recipient){
            print "Sending SMS:".$recipient['number'].", message=".$message.PHP_EOL;
            $this->notifyUserAggregate->sendSMSToOne($recipient['number'],$message,$recipient['operator']);
        }

        return true;
    }
}

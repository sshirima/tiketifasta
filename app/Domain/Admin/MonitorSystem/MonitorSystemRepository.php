<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 2/11/2019
 * Time: 6:06 PM
 */

namespace App\Domain\Admin\MonitorSystem;


use App\Models\ThirdpartServer;

class MonitorSystemRepository
{
    public function getListOfServersToMonitor(){
        return ThirdpartServer::select(ThirdpartServer::COLUMN_IP_ADDRESS, ThirdpartServer::COLUMN_SERVER_NAME,
        ThirdpartServer::COLUMN_PORT, ThirdpartServer::COLUMN_AVAILABILITY_STATUS)->get()->toArray();
    }

    public function updateServerAvailabilityStatusByIp($server_ip, $status){
        $server = ThirdpartServer::where([ThirdpartServer::COLUMN_IP_ADDRESS=>$server_ip])->first();
        $server[ThirdpartServer::COLUMN_AVAILABILITY_STATUS] = $status;
        $server->update();
        return $server->update();
    }

    public function getSMSNotificationConfigs(){
        return config('monitor_system.notifications.sms');
    }
}
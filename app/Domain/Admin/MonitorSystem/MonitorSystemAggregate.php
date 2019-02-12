<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 2/1/2019
 * Time: 8:06 PM
 */

namespace App\Domain\Admin\MonitorSystem;


use App\Domain\Admin\MonitorSystem\Services\RunCommandsHostServer;

class MonitorSystemAggregate
{
    use RunCommandsHostServer;

    protected $repository;

    public function __construct(MonitorSystemRepository $repository)
    {
        $this->repository = $repository;
    }

    public function pingServerIp($ip, $count = 4){
        return $this->pingIp($ip, $count);
    }

    public function telnetServer($host, $port){
        return $this->telnetIp($host, $port);
    }

    public function getListOfServersToMonitor(){
        return $this->repository->getListOfServersToMonitor();
    }

    public function setServerAvailabilityStatus($server_ip, $status){

        return $this->repository->updateServerAvailabilityStatusByIp($server_ip, $status);
    }

    public function getSMSNotificationConfigs(){
        return $this->repository->getSMSNotificationConfigs();
    }
}
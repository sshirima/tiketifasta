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

    public function pingServerIp($ip, $count = 4){
        return $this->pingIp($ip, $count);
    }
}
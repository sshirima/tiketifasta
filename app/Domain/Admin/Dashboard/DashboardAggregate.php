<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 2/7/2019
 * Time: 12:28 PM
 */

namespace App\Domain\Admin\Dashboard;


use App\Models\Ticket;

class DashboardAggregate
{

    use DashboardRepository;

    public function getDashBoardData(){

        return [
            'servers'=>$this->getServerNames(),
            'server_ips'=>$this->getServerIps(),
            'merchants_active_count'=>$this->getActiveMerchantCount(),
            'buses_count'=>$this->getBusesCountAll(),
            'tickets_active_count'=>$this->getActiveTicketCounts(),
        ];
    }


    /**
     * @return array
     */
    private function getServerIps(){
        $ips = [];
        foreach ($this->getServers() as $key=>$server){
            $ips[] = $key;
        }
        return $ips;
    }

    /**
     * @return array
     */
    private function getServerNames(){
        return $this->getServers();
    }

    private function getActiveMerchantCount(){
        return $this->getMerchantCountByStatus(1);
    }

    private function getActiveTicketCounts(){
        return $this->getTicketsCountByStatus(Ticket::STATUS_CONFIRMED);
    }
}
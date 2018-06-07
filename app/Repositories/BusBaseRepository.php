<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 6/1/2018
 * Time: 11:22 PM
 */

namespace App\Repositories;


use App\Models\Bus;
use App\Models\Merchant;
use App\Models\Seat;

trait BusBaseRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return Bus::class;
    }

    /**
     * @param $bus
     * @param $seatRepository
     */
    protected function createBusSeats($bus, $seatRepository){
        if ($bus->seats()->count() == 0){
            Seat::createBusSeats($bus[Bus::COLUMN_ID],$bus[Bus::COLUMN_BUSTYPE_ID], $seatRepository);
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getBusInformation($id){
        $bus = $this->with(['merchant','busType'])->findWithoutFail($id);
        $bus->seats = $bus->seatArray(Seat::seatArrangementArray($bus->busType->seat_arrangement));
        return $bus;
    }

    /**
     * @param $id
     * @return bool
     */
    protected function deleteBus($id){
        $bus = $this->findWithoutFail($id);

        if (empty($bus)){
            return false;
        }
        $bus->delete();
        return true;
    }

    /**
     * @param $table
     * @return mixed
     */
    public function setCustomTable($table){

        $table->addQueryInstructions(function ($query) {
            $query->select($this->entityColumns)
                ->join(Merchant::TABLE,Merchant::ID,'=',Bus::MERCHANT_ID)
                ->where($this->conditions);
        });

        return $table;
    }

}
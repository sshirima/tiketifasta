<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 12/1/2018
 * Time: 6:03 PM
 */

namespace App\Services\Merchants;


use App\Models\Bus;
use App\Models\Merchant;
use App\Models\Schedule;
use App\Models\Trip;
use Illuminate\Support\Facades\Log;

trait MerchantAuthorization
{

    /**
     * @param Merchant $merchant
     * @param $status
     * @param bool $updateDependencies
     * @return bool
     */
    public function authorizeMerchant(Merchant $merchant, $status, $updateDependencies=true){

        $this->updateMerchantStatus($merchant, $status);

        if($updateDependencies){
            $this->updateMerchantAccountDependencies($merchant, $status);
        }

        return true;
    }

    /**
     * @param Merchant $merchant
     * @param $status
     */
    private function updateMerchantStatus(Merchant $merchant, $status){
        $merchant->status = $status;
        $merchant->update();
    }

    /**
     * @param Trip $trip
     * @param $status
     */
    private function updateTripStatus(Trip $trip, $status){
        $trip->status = $status;
        $trip->update();
    }

    /**
     * @param Schedule $schedule
     * @param $status
     */
    private function updateScheduleStatus(Schedule $schedule, $status){
        $schedule->status = $status;
        $schedule->update();
    }

    /**
     * @param Bus $schedule
     * @param $state
     */
    private function updateBusState(Bus $schedule, $state){
        $schedule->state = $state;
        $schedule->update();
    }

    /**
     * @param $status
     * @param $bus
     */
    protected function updateBusSchedulesStatus($status, $bus): void
    {
        $schedules = $bus->schedules()->where(['status' => !$status])->get();

        foreach ($schedules as $schedule) {
            $this->updateScheduleStatus($schedule, $status);
        }

       // Log::info('{'. count($schedules).'} schedules for bus {'.$bus->reg_number.'} status changed to  {'.$status.'}');
    }

    /**
     * @param $status
     * @param $bus
     */
    protected function updateBusTripsStatus($status, $bus): void
    {
        $trips = $bus->trips()->get();

        foreach ($trips as $trip) {
            $this->updateTripStatus($trip, $status);
        }

       // Log::info('{'. count($trips).'} trips for bus {'.$bus->reg_number.'}, status changed to  {'.$status.'}');
    }

    /**
     * @param Merchant $merchant
     * @param $status
     */
    protected function updateMerchantAccountDependencies(Merchant $merchant, $status): void
    {
        $buses = $merchant->buses()->get();

        $state = $status ? Bus::STATE_DEFAULT_ENABLED : Bus::STATE_DEFAULT_DISABLED;

        foreach ($buses as $bus) {

            $this->updateBusState($bus, $state);

            $this->updateBusTripsStatus($status, $bus);

            $this->updateBusSchedulesStatus($status, $bus);
        }

       // Log::info('{'. count($buses).'} buses for merchant {'.$merchant->name.'} state changed to  {'.$state.'}');
    }
}
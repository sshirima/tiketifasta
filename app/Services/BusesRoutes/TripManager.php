<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 6/5/2018
 * Time: 10:59 PM
 */

namespace App\Services\BusesRoutes;


use App\Models\Trip;

trait TripManager
{
    protected $goingTrips = array();
    protected $returnTrips = array();
    protected $ignoredTrips = array();
    protected $sortedTrips = array();

    /**
     * @param array $trips
     * @return array
     */
    public function analyseTrips(array $trips){

        $this->goingTrips = array();
        $this->returnTrips = array();
        $this->ignoredTrips = array();
        $this->sortedTrips = array();

        $startLocation = $this->initializeStartLocation($trips);

        $this->getTripsByDirections($trips, $startLocation);

        $this->getSortedTrips();

        foreach ($this->sortedTrips as $key=>$trip){

            if ($trip['source'] == $startLocation){
                $trip[Trip::COLUMN_DIRECTION] = 'GO';
            } else if ($trip['destination'] == $startLocation){
                $trip[Trip::COLUMN_DIRECTION] = 'RETURN';
            } else {
                $trip[Trip::COLUMN_DIRECTION] = 'UNKNOWN';
            }
            $trip[Trip::COLUMN_STATUS] = 0;

            $this->sortedTrips[$key] = $this->getConvertedTime($trip);
        }

        return $this->sortedTrips;
    }

    /**
     * @param array $trips
     * @return mixed
     */
    protected function initializeStartLocation(array $trips)
    {
        $startLocation=0;

        if (count($trips) > 0) {
            $startLocation = $trips[0]['source'];
        }
        return $startLocation;
    }

    /**
     * @param array $trips
     * @param $startLocation
     */
    protected function getTripsByDirections(array $trips, $startLocation): void
    {
        foreach ($trips as $trip) {

            if ($trip['source'] == $startLocation) {
                $this->goingTrips[] = $trip;
            } else

                if ($trip['destination'] == $startLocation) {
                    $this->returnTrips[] = $trip;
                } else {
                    $this->ignoredTrips[] = $trip;
                }
        }
    }

    /**
     *
     */
    protected function getSortedTrips(): void
    {
        $tr = array();

        foreach ($this->goingTrips as $goingTrip) {
            //Create opposite route
            $this->sortedTrips[] = $goingTrip;

            $hasReturn = $this->getExistingReverseTrip($goingTrip);

            if ($hasReturn) {
                continue;
            } else {
                $tr = $this->getReverseDirectionTrip($goingTrip, $tr);
                $this->sortedTrips[] = $tr;
            }
        }
    }

    /**
     * @param $goingTrip
     * @param $tr
     * @return mixed
     */
    protected function getReverseDirectionTrip($goingTrip, $tr)
    {
        $tr['source'] = $goingTrip['destination'];
        $tr['destination'] = $goingTrip['source'];
        $tr['depart_time'] = $goingTrip['depart_time'];
        $tr['arrival_time'] = $goingTrip['arrival_time'];
        $tr['travelling_days'] = $goingTrip['travelling_days'];
        return $tr;
    }

    /**
     * @param $goingTrip
     * @return bool
     */
    protected function getExistingReverseTrip($goingTrip): bool
    {
        $hasReturn = false;
        foreach ($this->returnTrips as $returnTrip) {
            if ($goingTrip['source'] == $returnTrip['destination'] && $goingTrip['destination'] == $returnTrip['source']) {
                $this->sortedTrips[] = $returnTrip;
                $hasReturn = true;
                break;
            }
        }
        return $hasReturn;
    }

    /**
     * @param $tripArray
     * @return mixed
     */
    protected function getConvertedTime($tripArray)
    {
        $tripArray['depart_time'] = $this->convertTime($tripArray['depart_time']);
        $tripArray['arrival_time'] = $this->convertTime($tripArray['arrival_time']);
        return $tripArray;
    }

    /**
     * @param $time
     * @param string $format
     * @return false|string
     */
    public function convertTime($time, $format='H:i')
    {
        return date($format, strtotime($time));
    }

}
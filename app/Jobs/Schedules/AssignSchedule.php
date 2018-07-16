<?php

namespace App\Jobs\Schedules;

use App\Repositories\BusRepository;
use App\Repositories\DayRepository;
use App\Services\DateTimeService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AssignSchedule implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $request;
    protected $busId;

    /**
     * AssignSchedule constructor.
     * @param array $request
     * @param $busId
     */
    public function __construct(Array $request, $busId)
    {
        $this->request = $request;
        $this->busId = $busId;
    }

    /**
     * @param BusRepository $busRepository
     * @param DayRepository $dayRepository
     */
    public function handle(BusRepository $busRepository, DayRepository $dayRepository)
    {
        $formattedDate = $this->formatDate($this->request['date']);

        $bus = $busRepository->findWithoutFail($this->busId);

        $day = $bus->scheduledDays()->where(['date'=>$formattedDate])->first();

        if (empty($day)){

            //Create date
            $day = $dayRepository->updateOrCreate(['date'=>$formattedDate]);

            //Assign day to the bus
            $bus->scheduledDays()->attach($day->id,['direction'=>$this->request['direction'],'status'=>1]);
        }
    }

    /**
     * @param $date
     * @return string
     */
    private function formatDate($date){
        return DateTimeService::convertDate($date,'Y-m-d' );
    }
}

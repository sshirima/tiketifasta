<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class AssignScheduleSeederTable extends Seeder
{

    use \App\Services\Schedules\AssignScheduleService;

    protected $faker;

    public function __construct(Faker $faker)
    {
        $this->faker = $faker;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Create busesTypes
        $buses = \App\Models\Bus::where(['state'=>'ENABLED'])->get();

        foreach ($buses as $bus){

            for($i=0;$i<2;$i++){
                $travellingDay = \Illuminate\Support\Carbon::createFromTimeStamp($this->faker->dateTimeBetween('-0 days', '+30 days')->getTimestamp());
                $returnDate = \Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s', $travellingDay)->addDay();

                /*print 'Travelling date: '.$travellingDay->format('Y-m-d').PHP_EOL;
                print '    Return date: '.$returnDate->format('Y-m-d').PHP_EOL;*/

                $response = $this->assignSchedule($bus, $travellingDay,'GO');
                $responseR = $this->assignSchedule($bus, $returnDate,'RETURN');

                print 'INFO: Going trip=>'.json_encode($response).PHP_EOL;
                print 'INFO: Return trip=>'.json_encode($responseR).PHP_EOL;
                print '=================================================================='.PHP_EOL;
            }
        }
    }
}

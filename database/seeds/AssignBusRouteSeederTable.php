<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use App\Models\Trip;

class AssignBusRouteSeederTable extends Seeder
{

    use \App\Services\BusesRoutes\TripManager;

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
        //Create bus trips
        $buses = \App\Models\Bus::where(['state'=>'ENABLED'])->whereNull('route_id')->get();

        $routes = \App\Models\Route::all();

         foreach ($buses as $bus){
             $analysed = [];
             $route = $this->faker->randomElement($routes);
             $trips = $this->generateTrips($bus, $route);
             $analysed = $this->analyseTrips([$trips]);

             $price = $this->faker->randomElement(['20000','25000','30000','35000','15000','40000']);

             $this->createTrips($analysed, $bus,$price);
             print 'INFO: Trips has been created'.PHP_EOL;

             $this->assignBusToRoute($bus, $route);
             print 'INFO: Route has been assigned to the bus successful'.PHP_EOL;
             print '===================================================='.PHP_EOL;
         }

    }

    /**
     * @param array $trips
     * @param $bus
     * @param $price
     * @return bool
     */
    public function createTrips(array $trips, $bus,$price)
    {
        foreach ($trips as $trip) {
            $trip[Trip::COLUMN_BUS_ID] = $bus->id;
            Trip::updateOrCreate([
                Trip::COLUMN_SOURCE => $trip[Trip::COLUMN_SOURCE],
                Trip::COLUMN_DESTINATION => $trip[Trip::COLUMN_DESTINATION],
                Trip::COLUMN_BUS_ID => $bus->id,
            ], [
                Trip::COLUMN_ARRIVAL_TIME => $trip[Trip::COLUMN_ARRIVAL_TIME],
                Trip::COLUMN_DEPART_TIME => $trip[Trip::COLUMN_DEPART_TIME],
                Trip::COLUMN_TRAVELLING_DAYS => $trip[Trip::COLUMN_TRAVELLING_DAYS],
                Trip::COLUMN_STATUS => $trip[Trip::COLUMN_STATUS],
                Trip::COLUMN_PRICE => $price,
                Trip::COLUMN_DIRECTION => $trip[Trip::COLUMN_DIRECTION],
            ]);
        }
        return true;
    }

    /**
     * @param $bus
     * @param $route
     */
    public function assignBusToRoute($bus, $route){
        $bus->route_id = $route->id;
        $bus->update();
    }

    /**
     * @param $bus
     * @param $route
     * @return array
     */
    public function generateTrips($bus, $route){
        return [
            'source'=>$route->start_location,
            'destination'=>$route->end_location,
            'depart_time'=>'0'.random_int(4, 9).':'.random_int(10,59),
            'arrival_time'=>random_int(12, 23).':'.random_int(10,59),
            //'bus_id'=>$bus->id,
            'travelling_days'=>1,
            //'price'=>$this->faker->randomElement(['20000','25000','30000','35000','15000','40000']),
            //'status'=>0,
            'direction'=>'GO',
        ];
    }
}

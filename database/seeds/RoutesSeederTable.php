<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class RoutesSeederTable extends Seeder
{

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
        $startLocation = \App\Models\Location::where(['locations.name'=>'Dar es Salaam'])->first();

        $endLocations = \App\Models\Location::whereNotIn('locations.name',['Dar es Salaam'])->get();

        for ($i=0; $i<10;$i++){
            $this->createRoutes($startLocation, $this->faker->randomElement($endLocations));
            print 'INFO: Route has been created'.PHP_EOL;
            print '============================='.PHP_EOL;
        }
    }

    /**
     * @param $startLocation
     * @param $endLocation
     * @return mixed
     */
    public function createRoutes($startLocation, $endLocation){

        $route = \App\Models\Route::create([
            'route_name'=>$startLocation->name.' - '.$endLocation->name,
            'start_location'=>$startLocation->id,
            'end_location'=>$endLocation->id,
        ]);

        $route->locations()->attach($startLocation->id);
        $route->locations()->attach($endLocation->id);
    }
}

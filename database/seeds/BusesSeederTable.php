<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class BusesSeederTable extends Seeder
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
        //Get available merchants
        $merchants = \App\Models\Merchant::select('merchants.id','merchants.name as merchant_name')
            ->where('merchants.contract_end','>=',date('Y-m-d'))->get();

        foreach ($merchants as $merchant){

            $size = random_int(1,3);

            for($i=0;$i<=$size;$i++){
                $bus = $this->createBus($merchant);
                print 'INFO: Bus created'.PHP_EOL;

                $this->createBusSeats($bus);
                print 'INFO: Bus seats created'.PHP_EOL;
            }
        }
    }

    /**
     * @param $bus
     */
    public function createBusSeats($bus){
        if ($bus->seats()->count() == 0){
            \App\Models\Seat::createBusSeats($bus['id'],$bus['bustype_id']);
        }
        print 'INFO: Bus seats created'.PHP_EOL;
    }

    /**
     * @param $merchant
     * @return mixed
     */
    public function createBus($merchant){
        $initialChar = ['A','B','C','D'];
        $precedingChar = ['A','B','C','D','E','F','G','K','L','M','X','Y','Z'];

        return \App\Models\Bus::create([
            'reg_number' => 'T'.rand(100, 999).$this->faker->randomElement($initialChar).$this->faker->randomElement($precedingChar).
                $this->faker->randomElement($precedingChar),
            'bustype_id' => rand(1, 3),
            'merchant_id' => $merchant->id,
            'driver_name' => $this->faker->name,
            'conductor_name' => $this->faker->name,
            'state' => 'ENABLED',
            'condition' => 'OPERATIONAL',
        ]);
    }
}

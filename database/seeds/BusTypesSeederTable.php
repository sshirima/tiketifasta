<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class BusTypesSeederTable extends Seeder
{

    protected $faker;

    const SEAT_ARRANGEMENT = ['e___e,ee_ee,ee_ee,ee_ee,ee_ee,ee_ee,ee_ee,ee_ee,ee_ee,ee_ee,ee_ee,ee_ee,ee_ee,eeeee',
        'e___e,ee_ee,ee_ee,ee_ee,ee_ee,ee_ee,ee_ee,ee_ee,ee_ee,ee_ee,ee_ee,eeeee',
        'e___e,ee_ee,ee_ee,ee_ee,ee_ee,ee_ee,ee_ee,ee_ee,ee_ee,ee_ee,eeeee',
        'e___e,ee_ee,ee_ee,ee_ee,ee_ee,ee_ee,ee_ee,ee_ee,ee_ee,ee_ee,ee_ee,ee_ee,eeeee',
        'e___e,ee_ee,ee_ee,ee_ee,ee_ee,ee_ee,ee_ee,ee_ee,ee_ee,ee_ee,ee_ee,eeeee',
    ];

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
        for($i=0;$i<10;$i++){
            $this->createBusType();
            print 'INFO: BusType created'.PHP_EOL;
            print '======================='.PHP_EOL;
        }
    }

    /**
     * @return mixed
     */
    public function createBusType(){
        $busName = ['Marcopolo','Yutong','Higer','Scania'];
        $seatArr = $this->faker->randomElement(self::SEAT_ARRANGEMENT);
        $seatSize = substr_count($seatArr,'e');

        return \App\Models\Bustype::create([
            'name'=>$this->faker->randomElement($busName).'('.$seatSize.' seats)',
            'seats'=>$seatSize,
            'seat_arrangement'=>$seatArr
        ]);
    }
}

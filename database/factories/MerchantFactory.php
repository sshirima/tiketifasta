<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(\App\Models\Merchant::class, function (Faker $faker) {

    $startDate = \Illuminate\Support\Carbon::createFromTimeStamp($this->faker->dateTimeBetween('-30 days', '+30 days')->getTimestamp());
    $endDate = \Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s', $startDate)->addYear(1);

    return [
        'name'=>$faker->company,
        'contract_start'=>$startDate->format('Y-m-d'),
        'contract_end'=>$endDate->format('Y-m-d'),
        'status'=>1,
    ];
});

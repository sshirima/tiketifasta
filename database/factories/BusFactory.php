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

$factory->define(\App\Models\Bus::class, function (Faker $faker) {
    $merchantIds = [2,17,1,18];
    $initiaChar = ['A','B','C','D'];
    $preccedingChar = ['A','B','C','D','E','F','G','K','L','M','X','Y','Z'];

    return [
        'reg_number' => 'T'.rand(100, 999).$initiaChar[rand(0, 3)].$preccedingChar[rand(0, 12)].$preccedingChar[rand(0, 12)],
        'bustype_id' => rand(1, 3),
        'merchant_id' => $merchantIds[rand(0, 3)],
        'driver_name' => $faker->name,
        'conductor_name' => $faker->name,
        'state' => 'ENABLED',
        'condition' => 'OPERATIONAL',
    ];
});

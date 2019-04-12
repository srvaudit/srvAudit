<?php

use Carbon\Carbon;
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

$factory->define(App\Team::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'owner_id' => 1,
        'trial_ends_at' => Carbon::now()->addWeek(),
    ];
});

<?php

use Faker\Generator as Faker;

$factory->define(App\Session::class, function (Faker $faker) {
    $user = [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
    ];

    return [
        'sid' => str_random(5),
        'user_id' => App\User::create($user),
        'hostname' => str_random(5),
        'username' => $faker->name,
        'ticket_url' => $faker->url,
        'notes' => $faker->sentence
    ];
});

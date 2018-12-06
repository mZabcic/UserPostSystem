<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

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

$factory->define(App\Post::class, function (Faker $faker) {
    $users = App\User::pluck('id')->toArray();
    return [
        'title' => $faker->sentence(6, true) ,
        'content' => $faker->sentence(8, true) ,
        'user_id' => $faker->randomElement($users)
    ];
});

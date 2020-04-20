<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\User;
use Illuminate\Support\Str;
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

$factory->define(User::class, function (Faker $faker) {
    $curr_time = now();
    return [
        'username' => env('APP_USERNAME'), //$faker->name,
        'firstname' => env('APP_FIRSTNAME'),
        'email' => env('APP_EMAIL'), //$faker->unique()->safeEmail,
        'email_verified_at' => $curr_time,
        'password' => env('APP_PASSWORD'),
        'remember_token' => Str::random(10),
        'created_at' => $curr_time,
        'updated_at' => $curr_time,
    ];
});

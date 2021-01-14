<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use App\Model;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(App\Product::class, function (Faker $faker) {
    $linedot = $faker->numberBetween(10, 60);
    $color = $faker->numberBetween(1, 12);
    return [
        'productname' => Str::random(10),
        'user_id' => factory(App\User::class)->create()->id,
        'linedot' => $linedot,
        'alldot' => $linedot * $linedot,
        'colors' => str_repeat("#{$color}_", $linedot * $linedot),
        'created_at' => $faker->dateTime(),
        'updated_at' => $faker->dateTime(),
        'uniquekey' => Str::random(20),
    ];
});

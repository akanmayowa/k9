<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Site;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Site::class, function (Faker $faker) {
    return [
        'id' => $faker->unique()->regexify('^[1-9]{6}$'),
        'name' => $faker->name,
    ];
});

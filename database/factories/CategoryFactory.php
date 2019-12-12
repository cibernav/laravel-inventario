<?php

use Faker\Generator as Faker;
use App\Category;

$factory->define(Category::class, function (Faker $faker) {
    return [
        //
        'name' => substr(ucfirst($faker->text),0, 60)

    ];
});

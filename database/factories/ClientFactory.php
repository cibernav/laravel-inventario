<?php

use Faker\Generator as Faker;
use App\Client;

$factory->define(Client::class, function (Faker $faker) {
    return [
        //


            'nombre' => $faker->sentence(4),
            'documento' => substr($faker->creditCardNumber,0,11),
            'email' => $faker->email,
            'telefono' => $faker->phoneNumber,
            'direccion' => $faker->address,
            'fecha_nacimiento' => $faker->dateTime,
            'compras' => $faker->randomDigit,
            'fecha' => $faker->dateTime
    ];
});

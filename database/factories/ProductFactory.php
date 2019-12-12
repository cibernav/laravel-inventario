<?php

use Faker\Generator as Faker;
use App\Product;

$factory->define(Product::class, function (Faker $faker) {

    $category_id = (int)$faker->numberbetween(1, 5);
    static $id = 100;
    switch($category_id){
        case 1:
        $codigo = 'A';
        break;
        case 2:
        $codigo = 'B';
        break;
        case 3:
        $codigo = 'C';
        break;
        case 4:
        $codigo = 'D';
        break;
        case 5:
        $codigo = 'E';
        break;
    }
    return [
        //
        'codigo' => strval($id++),
        'descripcion' => $faker->sentence(20),
        'categoria_id' => (int)$faker->numberbetween(1, 5),
        'stock' => $faker->randomDigit(50),
        'precio_compra' =>$faker->randomFloat(2, 5, 100),
        'precio_venta' =>$faker->randomFloat(2, 5, 100),
        'ventas' => $faker->randomDigit,
        'fecha' => $faker->dateTime
    ];
});

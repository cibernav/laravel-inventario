<?php

use Illuminate\Database\Seeder;
use App\Category;
use App\Product;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $categories = factory(Category::class, 5)->create();
        $categories->each(function ($c){
            for ($i=0; $i <= 40; $i++) {

                $products = factory(Product::class, 1)->make(['codigo' => $c->id .  (string)($i < 10 ? '0'.(string)$i : $i) ]); //->make(['codigo' => substr($c->name,0,1)]);
                $c->products()->saveMany($products);
            };

            //$c->products()->saveMany($products);

        });
    }
}

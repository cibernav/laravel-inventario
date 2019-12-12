<?php

use Illuminate\Database\Seeder;
Use App\Client;

class ClientTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        factory(Client::class, 300)->create();
    }
}

<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Storage;
use App\User;

class UserRolTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        //User::truncate();
        //Role::truncate();

        Storage::deleteDirectory('public/img');;

        $user = new User();
        $user->name = "Administrador";
        $user->email = "admin@mail.com";
        $user->password = bcrypt('123456');
        $user->estado = true;
        $user->save();

        $rol = Role::create(['name' => 'Administrador']);

        Role::create(['name' => 'Vendedor']);
        Role::create(['name' => 'Especial']);
        $user->assignRole($rol);


        factory(User::class, 20)->create();
    }
}

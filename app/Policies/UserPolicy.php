<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    public function before($user, $ability){
		if($user->isAdmin()){
            return true;
        }
		//si devuelve true , anula todas las demas validaciones de abajo.
    }

    public function index(){

    }

    public function edit(User $authUser, User $user){
        return $authUser->id === $user->id; //return true/false
        //$authUser, se injecta automaticamente
    }

    public function destroy(User $authUser, User $user){
        return $authUser->id === $user->id;
    }
}

<?php

namespace App\Policies;

use App\User;
use App\DocumentoCabecera;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocumentoCabeceraPolicy
{
    use HandlesAuthorization;


    public function before($user, $ability){
        if($user->isAdmin())
            return true;
    }
    /**
     * Determine whether the user can view the documento cabecera.
     *
     * @param  \App\User  $user
     * @param  \App\DocumentoCabecera  $documentoCabecera
     * @return mixed
     */
    public function view(User $user, DocumentoCabecera $documentoCabecera)
    {
        //
    }

    /**
     * Determine whether the user can create documento cabeceras.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the documento cabecera.
     *
     * @param  \App\User  $user
     * @param  \App\DocumentoCabecera  $documentoCabecera
     * @return mixed
     */
    public function update(User $user, DocumentoCabecera $documentoCabecera)
    {
        //
        if($user->isAdmin())
            return true;
    }

    /**
     * Determine whether the user can delete the documento cabecera.
     *
     * @param  \App\User  $user
     * @param  \App\DocumentoCabecera  $documentoCabecera
     * @return mixed
     */
    public function delete(User $user, DocumentoCabecera $documentoCabecera)
    {
        //
        if($user->isAdmin())
            return true;
    }

    /**
     * Determine whether the user can restore the documento cabecera.
     *
     * @param  \App\User  $user
     * @param  \App\DocumentoCabecera  $documentoCabecera
     * @return mixed
     */
    public function restore(User $user, DocumentoCabecera $documentoCabecera)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the documento cabecera.
     *
     * @param  \App\User  $user
     * @param  \App\DocumentoCabecera  $documentoCabecera
     * @return mixed
     */
    public function forceDelete(User $user, DocumentoCabecera $documentoCabecera)
    {
        //
    }
}

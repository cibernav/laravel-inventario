<?php

namespace App;

use Carbon\Carbon;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /*
    public function roles(){
        $this->belongsToMany(Roles::class, 'rol_user');
    }
    */

    public static $rules = [
        'name' => 'required|string|max:100',
        'email' => 'required|string|email|max:200|unique:users',
        'password' => 'required|string|min:6',
        'role' => 'required',
        'photo' => 'nullable|mimes:jpeg,png|file|max:2000000'
    ];


    public static function rulesUpdate($id){
        return [
            'name' => 'required|string|max:100',
            'email' => ['required', 'string', 'email', 'max:200', 'unique:users,email,'.$id],
            'password' => 'nullable|string|min:6',
            'role' => 'required',
            'photo' => 'nullable|mimes:jpeg,png|max:2000000'
        ];
    }

    public function isAdmin(){
        //forma 1
        //hasRole usa el namespace Spatie\Permission\Traits\HasRoles
        return $this->hasRole(['Administrador']);
    }

    public function getPhotoAttribute(){
        $imagen = $this->foto;
        if($imagen)
            return $imagen;
        else
            return '/storage/anonymous_user.png';
    }

    public function getDateLoginAttribute(){

        $fecha = $this->fechalogin;
        if($fecha){
            return Carbon::parse($fecha)->Format('d-m-Y h:m:s');
        }

        return '';

    }

    public function hasRoles(array $roles){
        // forma 2
        // Campo role en tabla user
        foreach ($roles as $role) {
            if($this->role === $role){
                return true;
            }
        }

        return false;
    }

}

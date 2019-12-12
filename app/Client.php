<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    //

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'nombre', 'documento', 'email', 'telefono', 'direccion', 'fecha_nacimiento', 'compras', 'ultima_compra', 'fecha'];

    public static $rules = [
        'nombre' => 'required|string|min:5',
        'documento' => 'required|integer|digits_between:5,11|unique:clients,documento',
        'email' => 'required|email',
        'telefono' => 'required|string|min:6',
        'direccion' => 'required|string|min:5',
        'fecha_nacimiento' => 'required|date_format:d/m/Y'
    ];

    public static function rulesUpdate($id){
        return [
            'nombre' => 'required|string|min:5',
            'documento' => 'required|integer|digits_between:5,11|unique:clients,documento,'. $id,
            'email' => 'required|email',
            'telefono' => 'required|string|min:6',
            'direccion' => 'required|string|min:5',
            'fecha_nacimiento' => 'required|date_format:d/m/Y'
        ];
    }

}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Category;

class Product extends Model
{
    //

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'codigo', 'descripcion', 'categoria_id', 'imagen','stock', 'precio_compra',
        'precio_venta', 'ventas', 'fecha'
    ];

    public function category(){
        return $this->belongsTo(Category::class, 'categoria_id');
    }

    public static $rules = [
        'codigo' => 'required|string|unique:products,codigo',
        'descripcion' => 'required|string|min:5|unique:products,descripcion',
        'categoria_id' => 'required|integer',
        'imagen' => 'nullable|mimes:jpeg,png|file|max:2000000',
        'stock' => 'required|integer',
        'precio_compra' => 'required|numeric|gt:0',
        'precio_venta' => 'required|numeric|gt:0'
    ];


    public static function rulesUpdate($id){
        return [
            'codigo' => 'required|string|unique:products,codigo,'. $id,
            'descripcion' => 'required|string|min:5|unique:products,descripcion,'. $id,
            'categoria_id' => 'required|integer',
            'imagen' => 'nullable|mimes:jpeg,png|file|max:2000000',
            'stock' => 'required|integer',
            'precio_compra' => 'required|numeric|gt:0',
            'precio_venta' => 'required|numeric|gt:0'
        ];
    }

    public static $messages = [
        'categoria_id.required' => 'El campo categoria es requerido'
    ];

    public function getPhotoAttribute(){
        $imagen = $this->imagen;
        if($imagen)
            return $imagen;
        else
            return '/storage/anonymous_prod.png';
    }
    // selector
    // public function getImagenAttribute($value){
    //     if($value)
    //         return $value;
    //     else
    //         return '/storage/anonymous_prod.png';
    // }

    public function getCategoriaNameAttribute(){

        return $this->category->name;
    }

}

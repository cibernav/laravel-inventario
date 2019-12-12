<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Product;

class Category extends Model
{
    //

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    public static $rules = [
        'name' => 'required|min:3|unique:categories'
    ];

    public static function rulesUpdate($id){

        return [
            'name' => 'required|min:3|unique:categories,name,'.$id
        ];
    }


    public function products()
    {
        return $this->hasMany(Product::class, 'categoria_id');
    }
}

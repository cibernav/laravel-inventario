<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentoDetalle extends Model
{
    //

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'documentodetalle';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id_documentocabecera', 'id_producto', 'cantidad', 'precio_unitario', 'importe'];
}

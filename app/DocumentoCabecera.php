<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentoCabecera extends Model
{
    //

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'documentocabecera';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_tipodocumento',
        'serie',
        'numero',
        'id_cliente',
        'nrodocumento',
        'fechaemision',
        'id_metodopago',
        'detallepago',
        'subtotal',
        'igv',
        'total',
        'id_user'
    ];

    public static $rules = [
        'serie' => 'required',
        'numero' => 'required|integer',
        'id_cliente' => 'required|integer',
        'id_metodopago' => 'required|string'

    ];

    public function items(){
        return $this->hasMany(DocumentoDetalle::class, 'id_documentocabecera');
    }

    public function getMetodoPagoAttribute(){
        $id = $this->id_metodopago;
        switch ($id) {
            case 1:
                $metodo = 'EFECTIVO';
                break;
            case 2:
                $metodo = 'TARJETA CREDITO';
                break;
            case 3:
                $metodo = 'TARJETA DEBITO';
                break;
            default:
                $metodo = 'NN';
                # code...
                break;
        }

        return $metodo;

    }

    public function user(){
        return $this->hasOne(User::class, 'id', 'id_user');
    }

    public function client(){
        return $this->hasOne(Client::class, 'id', 'id_cliente');
    }
}

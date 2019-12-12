<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentoCabecerasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentocabecera', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_tipodocumento');
            $table->string('serie');
            $table->string('numero');
            $table->integer('id_cliente')->unsigned()->nullable();
            $table->foreign('id_cliente')->references('id')->on('clients');
            $table->string('nrodocumento');
            $table->timestamp('fechaemision');
            $table->integer('id_metodopago');
            $table->string('detallepago');
            $table->float('subtotal');
            $table->float('igv');
            $table->float('total');
            $table->integer('id_user')->unsigned()->nullable();
            $table->foreign('id_user')->references('id')->on('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documento_cabeceras');
    }
}

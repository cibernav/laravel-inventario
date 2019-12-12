<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentoDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentodetalle', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_documentocabecera')->unsigned()->nullable();
            $table->foreign('id_documentocabecera')->references('id')->on('documentocabecera');
            $table->integer('id_producto')->unsigned()->nullable();
            $table->foreign('id_producto')->references('id')->on('products');
            $table->integer('cantidad');
            $table->float('precio_unitario');
            $table->float('importe');
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
        Schema::dropIfExists('documento_detalles');
    }
}

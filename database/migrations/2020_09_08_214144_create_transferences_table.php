<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transferences', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('register_id')->unsigned();             // Caja Registradora
            $table->integer('cashier_id')->unsigned();              // Cajero que la crea
            $table->float('amount', 8, 2)->default('0');            // Importe
            $table->string('folio',10)->nullable();             // Folio o Referencia
            $table->integer('subidiary_id_destination')->unsigned();// Sucursal Destino 
            $table->integer('cashier_id_destination')->unsigned();  // Cajero que recibe
            $table->timestamp('received_date');                     // Fecha recibico
            $table->boolean('open')->default('1'); 
            $table->softDeletes();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transferences');
    }
}

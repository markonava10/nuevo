<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('register_id')->unsigned();         // Caja
            $table->integer('cashier_id')->unsigned();          // Cajero
            $table->integer('key_movement_id')->unsigned();     // Clave Movimiento
            $table->float('amount', 8, 2)->default('0');        // Importe 
            $table->string('reference',15)->nullable();
            $table->integer('transactiontable_id')->unsigned(); // Id Foránea
            $table->string('transactiontable_type')->nullable(); // Modelo
            $table->softDeletes();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            // Las llaves foraneas
            $table->foreign('register_id')->references('id')->on('registers')->onDelete('cascade');
            $table->foreign('cashier_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('key_movement_id')->references('id')->on('key_movements')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movements');
    }
}

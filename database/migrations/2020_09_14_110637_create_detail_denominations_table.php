<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailDenominationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_denominations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('movement_id')->unsigned();         // Id Movimiento
            $table->integer('denomination_id')->unsigned();     // Id Denominación
            $table->integer('quantity')->unsigned();            // Cantidad
            $table->softDeletes();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            // Las llaves foraneas
            $table->foreign('movement_id')->references('id')->on('movements')->onDelete('cascade');
            $table->foreign('denomination_id')->references('id')->on('denominations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_denominations');
    }
}

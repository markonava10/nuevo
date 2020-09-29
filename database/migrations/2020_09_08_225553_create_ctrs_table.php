<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCtrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ctrs', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('service_id')->constrained();
            $table->timestamp('from_date');
            $table->timestamp('to_date');
            $table->integer('quantity')->default();
            $table->float('total', 8,2)->default();
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
        Schema::dropIfExists('ctrs');
    }
}

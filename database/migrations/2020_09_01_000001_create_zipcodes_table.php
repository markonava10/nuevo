<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZipcodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zipcodes', function (Blueprint $table) {
            $table->integer('zipcode')->unsigned();
            $table->string('town',40);
            $table->string('type',10);
            $table->string('state',5);
            $table->string('county',50)->null();
            $table->string('timezone',30)->null();
            $table->string('areacode',30)->null();
            $table->double('latitude', 15, 8)->null();
            $table->double('longitude', 15, 8)->null();
            $table->string('region',2)->null();
            $table->string('country',5)->null();
            // Comandos
            $table->primary('zipcode');
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
        Schema::dropIfExists('zipcodes');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('service',50)->unique();
            $table->string('short',20)->unique();
            $table->boolean('charges')->default('0');
            $table->string('next_route',100)->nullable();
            $table->string('route_transaction',100)->nullable();
            $table->boolean('require_customer')->default('1');
            $table->boolean('require_provider')->default('1');
            $table->boolean('require_receiver')->default('1');
            $table->boolean('require_exchangerate')->default('1');
            $table->string('image',191)->nullable();
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
        Schema::dropIfExists('services');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receivers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',80);
            $table->string('email')->nullable();
            $table->string('address',60)->nullable();
            $table->string('phone',15)->nullable();
            $table->integer('company_id')->unsigned()->nullable();
            $table->integer('country_id')->unsigned();
            $table->integer('user_id')->unsigned()->nullable();
            $table->boolean('active')->default('1');
            $table->boolean('black_list')->default('0');
            $table->softDeletes();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            // Las llaves foraneas
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('receivers');
    }
}

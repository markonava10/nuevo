<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthorizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('authorizations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('subsidiary_id')->unsigned();
            $table->integer('service_id')->unsigned();
            $table->date('date');
            $table->string('reason');
            $table->integer('customer_id')->unsigned();
            $table->integer('receiver_id')->unsigned();
            $table->integer('cashier_id')->unsigned();
            $table->integer('authorizer_id')->unsigned();
            $table->float('amount_transaction', 10, 2);
            $table->float('amount_before', 10, 2);
            $table->integer('status_id')->unsigned();
            $table->enum('result', ['Aproved', 'Canceled']);
            $table->string('authorization_code');
            $table->softDeletes();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
           // Las llaves foraneas
            $table->foreign('subsidiary_id')->references('id')->on('subsidiaries')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');    
            $table->foreign('receiver_id')->references('id')->on('receivers')->onDelete('cascade');
            $table->foreign('cashier_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('authorizer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('authorizations');
    }
}

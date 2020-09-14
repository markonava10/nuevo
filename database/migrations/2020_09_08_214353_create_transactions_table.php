<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('subsidiary_id')->unsigned();
            $table->integer('customer_id')->unsigned();
            $table->integer('service_id')->unsigned();
            $table->integer('provider_id')->unsigned()->nullable();
            $table->integer('cashier_id')->unsigned();
            $table->decimal('amount', 10, 2)->default(0);
            $table->decimal('fixed_fee', 10, 2)->default(0);
            $table->decimal('comission_percentage', 4, 2)->default(0);
            $table->decimal('comission_amount', 10, 2)->default(0);
            $table->decimal('total_charges', 10, 2)->default(0);
            $table->integer('status_id')->unsigned();
            $table->integer('transactiontable_id')->unsigned();
            $table->string('transactiontable_type')->nullable();
            $table->softDeletes();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
           // Las llaves foraneas
            $table->foreign('subsidiary_id')->references('id')->on('subsidiaries')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('provider_id')->references('id')->on('providers')->onDelete('cascade');
            $table->foreign('cashier_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('transactions');
    }
}

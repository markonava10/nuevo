<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthorizationsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('authorizations', function (Blueprint $table) {
			$table->increments('id');
			$table->foreignId('subsidiary_id')->constrained();
			$table->foreignId('service_id')->constrained();
			$table->date('date');
			$table->string('reason');
			$table->foreignId('customer_id')->constrained();
			$table->foreignId('receiver_id')->constrained();
			$table->unsignedBigInteger('cashier_id');
			$table->unsignedBigInteger('authorizer_id');
			$table->float('amount_transaction', 10, 2);
			$table->float('amount_before', 10, 2);
			$table->float('amount_commission', 9,2);
			$table->float('amount_commission_before', 9,2);
			$table->float('percentage_transaction', 5,2);
  			$table->float('percentage_before', 5,2);
			$table->foreignId('status_id')->constrained();
			$table->enum('result', ['Aproved', 'Canceled']);
			$table->string('authorization_code');
			$table->softDeletes();
			$table->timestamp('created_at')->useCurrent();
			$table->timestamp('updated_at')->useCurrent();
			// Las llaves foraneas
			$table->foreign('cashier_id')->references('id')->on('users')->onDelete('cascade');
			$table->foreign('authorizer_id')->references('id')->on('users')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('authorizations');
	}
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('transactions', function (Blueprint $table) {
			$table->id();
			$table->foreignId('subsidiary_id')->constrained();
			$table->foreignId('customer_id')->constrained();
			$table->foreignId('service_id')->constrained();
			$table->foreignId('provider_id')->constrained();
			$table->foreignId('register_id')->constrained();
			$table->unsignedBigInteger('cashier_id');
			$table->decimal('amount', 10, 2)->default(0);
			$table->decimal('fixed_fee', 10, 2)->default(0);
			$table->decimal('comission_percentage', 4, 2)->default(0);
			$table->decimal('comission_amount', 10, 2)->default(0);
			$table->decimal('total_charges', 10, 2)->default(0);
			$table->foreignId('status_id')->constrained();
			$table->unsignedBigInteger('transactiontable_id');
			$table->string('transactiontable_type')->nullable();
			$table->softDeletes();
			$table->timestamp('created_at')->useCurrent();
			$table->timestamp('updated_at')->useCurrent();
			// Las llaves foraneas
			$table->foreign('cashier_id')->references('id')->on('users');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('transactions');
	}
}

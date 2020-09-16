<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyTypesPaymentsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('company_types_payments', function (Blueprint $table) {
			$table->foreignId('company_id')->constrained();
			$table->unsignedBigInteger('type_payment_id')->unsigned();
			$table->softDeletes();
			$table->timestamp('created_at')->useCurrent();
			$table->timestamp('updated_at')->useCurrent();
			// Foránea
			$table->foreign('type_payment_id')->references('id')->on('types_payments')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('company_types_payments');
	}
}

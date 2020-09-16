<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExchangeChargesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('exchange_charges', function (Blueprint $table) {
			$table->id();
			$table->foreignId('company_id')->constrained();
			$table->date('exchange_date');
			$table->float('exchange_rate', 9, 5);
			$table->timestamps();

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('exchange_charges');
	}
}

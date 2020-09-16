<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExchangesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('exchanges', function (Blueprint $table) {
			$table->id();
			$table->foreignId('issue_id')->constrained();
			$table->foreignId('bank_id')->constrained();
			$table->decimal('exchange_rate', 8, 4)->default(0);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('exchanges');
	}
}

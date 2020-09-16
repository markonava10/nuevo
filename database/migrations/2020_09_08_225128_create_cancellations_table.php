<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCancellationsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('cancellations', function (Blueprint $table) {
			$table->id();
			$table->foreignId('transaction_id')->constrained();
			$table->foreignId('reason_id')->constrained();
			$table->foreignId('user_id')->constrained();
			$table->string('voucher')->nullable()->default(Null);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('cancellations');
	}
}

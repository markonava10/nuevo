<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransfersTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('transfers', function (Blueprint $table) {
			$table->id();
			$table->foreignId('receiver_id')->constrained();
			$table->foreignId('payer_id')->constrained();
			$table->decimal('exchange_rate', 8, 4)->default(0);
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
	public function down() {
		Schema::dropIfExists('transfers');
	}
}

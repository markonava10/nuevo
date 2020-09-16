<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayersTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('payers', function (Blueprint $table) {
			$table->id();
			$table->string('payer', 50)->unique();
			$table->string('short', 10)->unique();
			$table->string('logo', 150)->nullable();
			$table->decimal('exchange_rate', 12, 4)->default(0);
			$table->foreignId('country_id')->constrained();
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
		Schema::dropIfExists('payers');
	}
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarkingHistoriesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('marking_histories', function (Blueprint $table) {
			$table->id();
			$table->foreignId('customer_id')->constrained();
			$table->foreignId('mark_id')->constrained();
			$table->foreignId('user_id')->constrained();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('marking_histories');
	}
}

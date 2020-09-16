<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailDenominationsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('detail_denominations', function (Blueprint $table) {
			$table->id();
			$table->foreignId('movement_id')->constrained();
			$table->foreignId('denomination_id')->constrained();
			$table->integer('quantity')->unsigned(); // Cantidad
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
		Schema::dropIfExists('detail_denominations');
	}
}

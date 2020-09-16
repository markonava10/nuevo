<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpeningsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('openings', function (Blueprint $table) {
			$table->id();
			$table->foreignId('register_id')->constrained();
			$table->unsignedBigInteger('cashier_id');

			$table->boolean('open')->default('1');
			$table->timestamps();
			// Las llaves foraneas
			$table->foreign('cashier_id')->references('id')->on('users')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('openings');
	}
}

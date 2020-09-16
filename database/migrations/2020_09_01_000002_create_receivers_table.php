<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiversTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('receivers', function (Blueprint $table) {
			$table->id();
			$table->string('first_name', 40);
			$table->string('last_name', 40);
			$table->string('email')->nullable();
			$table->string('phone', 15)->nullable();
			$table->foreignId('country_id')->constrained();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('receivers');
	}
}

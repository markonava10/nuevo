<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeyMovementsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('key_movements', function (Blueprint $table) {
			$table->id();
			$table->foreignId('company_id')->constrained();
			$table->integer('key')->unsigned();
			$table->string('spanish', 20)->nullable();
			$table->string('short_spanish', 6)->nullable();
			$table->string('english', 20)->nullable();
			$table->string('short_english', 6)->nullable();
			$table->enum('type', ['input', 'output'])->default('input');
			// Las llaves foraneas
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
		Schema::dropIfExists('key_movements');
	}
}

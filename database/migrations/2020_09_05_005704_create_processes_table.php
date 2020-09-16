<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('processes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained();
			$table->string('spanish', 25);
			$table->string('english', 25);
			$table->boolean('use_register')->default(1);
			$table->string('route', 100)->nullable();
			$table->boolean('detail_denominations')->default('0');
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
		Schema::dropIfExists('processes');
	}
}

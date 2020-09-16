<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTotalsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('totals', function (Blueprint $table) {
			$table->id();
			$table->foreignId('customer_id')->constrained();
			$table->unsignedBigInteger('service_id_1')->unsigned();
			$table->float('total1', 8, 2)->default('0'); // Importe
			$table->unsignedBigInteger('service_id_2')->unsigned();
			$table->float('total2', 8, 2)->default('0'); // Importe
			$table->softDeletes();
			$table->timestamp('created_at')->useCurrent();
			$table->timestamp('updated_at')->useCurrent();
			// Las llaves foraneas
			$table->foreign('service_id_1')->references('id')->on('services')->onDelete('cascade');
			$table->foreign('service_id_2')->references('id')->on('services')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('totals');
	}
}

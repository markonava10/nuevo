<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransferencesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('transferences', function (Blueprint $table) {
			$table->id();

			$table->foreignId('register_id')->constrained();
			$table->unsignedBigInteger('cashier_id'); // Cajero que la crea
			$table->float('amount', 8, 2)->default('0'); // Importe
			$table->string('folio', 10)->nullable(); // Folio o Referencia
			$table->unsignedBigInteger('subidiary_id_destination')->unsigned(); // Sucursal Destino
			$table->unsignedBigInteger('cashier_id_destination')->unsigned(); // Cajero que recibe
			$table->timestamp('received_date'); // Fecha recibico
			$table->boolean('open')->default(1);
			$table->softDeletes();
			$table->timestamp('created_at')->useCurrent();
			$table->timestamp('updated_at')->useCurrent();
			// Foráneas
			$table->foreign('cashier_id')->references('id')->on('users');
			$table->foreign('subidiary_id_destination')->references('id')->on('subsidiaries');
			$table->foreign('cashier_id_destination')->references('id')->on('users');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('transferences');
	}
}

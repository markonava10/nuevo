<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistersTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('registers', function (Blueprint $table) {
			$table->id();
			$table->foreignId('subsidiary_id')->constrained();
			$table->string('register', 20);
			$table->enum('type', ['N', 'S'])->default('N'); // N=Normal S=Safe (Fuerte)
			$table->float('amount_open', 8, 2)->default('0'); // Importe para abrir
			$table->float('amount_close', 8, 2)->default('0'); // Importe a dejar al cerrar
			$table->boolean('open')->default('0'); // ¿Está abierta o cerrada?
			$table->boolean('petty_cash_to_close')->default('0'); // Obligatorio hacer Petty Cash?
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
		Schema::dropIfExists('registers');
	}
}

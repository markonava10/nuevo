<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePoliciesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('policies', function (Blueprint $table) {
			$table->id();
			$table->foreignId('company_id')->constrained();
			$table->foreignId('service_id')->constrained();

			$table->enum('entity', ['customer', 'receiver', 'transaction', 'issue']);
			$table->string('policy', 50)->nullable();
			$table->enum('type_value', ['amount', 'quantity']);
			$table->integer('days')->default(0);
			$table->decimal('limit_allowed', 9, 2);
			$table->enum('value_to_count', ['amount', 'subsidiary_id', 'transactions', 'provider_id', 'payer_id', 'receiver_id', 'issue_id', 'customer_id']);
			$table->unsignedInteger('priorirty')->default(0);
			$table->enum('action_type', ['warning', 'terminal'])->default('warning');
			$table->string('message')->null();
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
		Schema::dropIfExists('policies');
	}
}

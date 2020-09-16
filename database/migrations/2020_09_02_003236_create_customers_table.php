<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('customers', function (Blueprint $table) {
			$table->id();
			$table->string('name', 80);
			$table->string('email')->null();
			$table->string('address', 60)->null();
			$table->integer('zipcode')->unsigned();
			$table->string('phone', 15)->null();
			$table->foreignId('company_id')->constrained();
			$table->foreignId('country_id')->constrained();
			$table->foreignId('user_id')->constrained();

			$table->boolean('active')->default('1');
			$table->boolean('black_list')->default('0');
			$table->boolean('first_time_exceds')->default('0');
			$table->softDeletes();
			$table->timestamp('created_at')->useCurrent();
			$table->timestamp('updated_at')->useCurrent();
			// Las llaves foraneas

			$table->foreign('zipcode')->references('zipcode')->on('zipcodes')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('customers');
	}
}

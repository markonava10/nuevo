<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubsidiariesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('subsidiaries', function (Blueprint $table) {
			$table->id();
			$table->string('subsidiary', 80)->unique();
			$table->string('address', 60)->null();
			$table->integer('zipcode')->unsigned();
			$table->string('phone', 15)->null();
			$table->boolean('active')->default('0');
			$table->foreignId('company_id')->constrained();
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
		Schema::dropIfExists('subsidiaries');
	}
}

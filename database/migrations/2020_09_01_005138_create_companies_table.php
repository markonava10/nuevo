<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company',80)->unique();
            $table->string('owner',80)->nullable();
            $table->string('email')->unique();
            $table->string('address',60)->nullable();
            $table->integer('zipcode')->unsigned();
            $table->string('phone',15)->nullable();
            $table->string('name_notify',60)->nullable();
            $table->decimal('amount_bd_transfers',12,2)->default(500);
            $table->decimal('amount_bd_exchanges',12,2)->default(100);
            $table->decimal('amount_to_require_id',12,2)->default(900);
            $table->decimal('amount_require_adicional_id',12,2)->default(2800);
            $table->integer('days_to_require_id')->default(30);
            $table->integer('days_check_to_authorization')->default(15);
            $table->integer('adicional_ids')->default(2);
            $table->decimal('amount_to_notify',12,2)->default(2800);
            $table->integer('days_to_notify')->default(30);
            $table->decimal('limit_by_day_transfers',12,2)->default(10000);
            $table->decimal('limit_by_day_exchanges',12,2)->default(500);
            $table->integer('times_subsidiares')->default(2);
            $table->integer('days_subsidiaries')->default(1);
            $table->decimal('amount_subsidiaries',12,2)->default(5000);
            $table->decimal('first_time_transfers',12,2)->default(2800);
            $table->decimal('first_time_exchanges',12,2)->default(500);
            $table->decimal('amount_requiere_income',12,2)->default(2800);
            $table->integer('providers_transfers')->default(2);
            $table->integer('days_providers_transfers')->default(10);
            $table->integer('providers_exchanges')->default(2);
            $table->integer('days_providers_exchanges')->default(10);
            $table->integer('max_subidiaries')->default(0);
            $table->string('logo')->null();
            $table->boolean('active')->default('0');
            $table->timestamp('expire_at')->null();
            $table->integer('process_open')->default();
            $table->integer('process_close')->default();
            $table->integer('tx_subsidiary')->default();
            $table->integer('tx_safe')->default();
            $table->integer('tx_register')->default();
            $table->integer('tx_petty_cash')->default();
            $table->integer('rx_subsidiary')->default();
            $table->integer('rx_safe')->default();
            $table->integer('rx_register')->default();
            $table->integer('tx_express')->default();
            $table->integer('ch_request')->default();
            $table->integer('ch_accept')->default();
            $table->integer('ch_change')->default();
            $table->integer('ch_receive')->default();
            $table->decimal('copies_rate',6,2)->default();
            $table->decimal('fax_nat_first_page_rate',6,2)->default();
            $table->decimal('fax_nat_extra_page_rate',6,2)->default();
            $table->decimal('fax_int_extra_page_rate',6,2)->default();
            $table->decimal('amount_max_by_mo',9,2)->default();
            $table->decimal('amount_to_req_serial_mo',9,2)->default();
            // Las llaves foraneas
            $table->foreign('zipcode')->references('zipcode')->on('zipcodes')->onDelete('cascade');
            $table->foreignId('service_id_payments')->constrained();
            $table->foreignId('service_id_diference')->constrained();
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
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}

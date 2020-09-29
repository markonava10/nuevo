<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChargesVipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charges_vips', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('company_id')->constrained();
            $table->foreignId('service_id')->constrained();
            $table->foreignId('vip_id')->constrained();
			$table->float('lower_limit', 8, 2);
			$table->float('upper_limit', 8, 2);
			$table->float('fixed_fee', 5, 2);
            $table->decimal('percentage', 5, 2);
            $table->decimal('discount_percentage', 5, 2);
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('charges_vips');
    }
}

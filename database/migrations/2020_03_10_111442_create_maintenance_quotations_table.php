<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaintenanceQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maintenance_quotations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('full_name')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('product_id')->nullable();
            $table->string('service_id')->nullable();
            $table->string('date_of_contract')->nullable();
            $table->text('type_of_complain')->nullable();
            $table->string('file')->nullable();
            $table->boolean('is_action')->default(false);
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
        Schema::dropIfExists('maintenance_quotations');
    }
}

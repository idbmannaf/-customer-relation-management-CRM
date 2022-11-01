<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('visit_plan_id')->nullable();
            $table->unsignedBigInteger('visit_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('device_name')->nullable();
            $table->string('brand')->nullable();
            $table->string('model_no')->nullable();
            $table->string('service_volt')->nullable();
            $table->string('capacity')->nullable();
            $table->string('serial_no')->nullable();
            $table->string('total_load')->nullable();
            $table->unsignedBigInteger('addedBy_id')->nullable();
            $table->unsignedBigInteger('editedBy_id')->nullable();
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
        Schema::dropIfExists('service_products');
    }
};

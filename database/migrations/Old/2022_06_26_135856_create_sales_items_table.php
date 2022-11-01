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
        Schema::create('sales_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->unsignedBigInteger('visit_plan_id')->nullable();
            $table->unsignedBigInteger('visit_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('product_name')->nullable();
            $table->string('product_warranty')->nullable();
            $table->string('product_capacity')->nullable();
            $table->string('product_backup_time')->nullable();
            $table->decimal('product_unit_price')->default(0.00);
            $table->decimal('product_quantity')->default(0.00);
            $table->decimal('product_final_price')->default(0.00);
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
        Schema::dropIfExists('sales_items');
    }
};

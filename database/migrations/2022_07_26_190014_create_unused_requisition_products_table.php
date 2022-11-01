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
        Schema::create('unused_requisition_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('visit_plan_id')->nullable();
            $table->unsignedBigInteger('visit_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->unsignedBigInteger('requisition_id')->nullable();
            $table->unsignedBigInteger('requisition_product_item_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('product_name')->nullable();
            $table->bigInteger('unit_price')->default(0);
            $table->bigInteger('quantity')->default(0);
            $table->bigInteger('total_price')->default(0);
            $table->string('type')->nullable(0);
            $table->string('status')->nullable();//repair,recharge,bad
            $table->string('repair_status')->nullable();//use =>if use then this reqisition product quantity add to the stock history,bad
            $table->string('recharge_status')->nullable();//use =>if use then this reqisition product quantity add to the stock history,bad

            $table->unsignedBigInteger('repair_by')->nullable();
            $table->timestamp('repair_at')->nullable();

            $table->unsignedBigInteger('recharge_by')->nullable();
            $table->timestamp('recharge_at')->nullable();

            $table->unsignedBigInteger('bad_by')->nullable();
            $table->timestamp('bad_at')->nullable();

            $table->unsignedBigInteger('repair_use_by')->nullable();
            $table->timestamp('repair_use_at')->nullable();

            $table->unsignedBigInteger('repair_bad_by')->nullable();
            $table->timestamp('repair_bad_at')->nullable();

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
        Schema::dropIfExists('unused_requisition_products');
    }
};

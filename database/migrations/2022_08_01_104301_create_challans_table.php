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
        Schema::create('challans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('visit_plan_id')->nullable();
            $table->unsignedBigInteger('visit_id')->nullable();
            $table->unsignedBigInteger('requisition_id')->nullable();
            $table->unsignedBigInteger('offer_id')->nullable();
            $table->string('challan_no')->nullable();
            $table->string('invoice_no')->nullable();
            $table->timestamp('date_time')->nullable();
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->string('buyer_phone')->nullable();
            $table->string('buyer_name')->nullable();
            $table->string('buyer_address')->nullable();
            $table->string('delivery_address')->nullable();
            $table->string('attention')->nullable();
            $table->string('payment_terms')->nullable();
            $table->string('s_order_no')->nullable();
            $table->string('payment_dead_line')->nullable();
            $table->text('remarks')->nullable();
            $table->text('buyer_ref_no')->nullable();
            $table->bigInteger('total_quantity')->nullable();
            $table->decimal('total_price',20,2)->nullable();
            $table->unsignedBigInteger('customer_signature')->nullable();
            $table->date('customer_signature_date')->nullable();
            $table->string('authorised_signatory')->nullable();
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
        Schema::dropIfExists('challans');
    }
};

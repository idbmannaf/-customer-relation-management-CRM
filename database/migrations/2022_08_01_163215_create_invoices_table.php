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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('visit_plan_id')->nullable();
            $table->unsignedBigInteger('visit_id')->nullable();
            $table->unsignedBigInteger('requisition_id')->nullable();
            $table->unsignedBigInteger('offer_id')->nullable();
            $table->string('invoice_no')->nullable();
            $table->date('invoice_date')->nullable();
            $table->string('challan_no')->nullable();
            $table->string('s_order_no')->nullable();
            $table->string('cnsignee')->nullable();
            $table->string('remarks')->nullable();
            $table->unsignedBigInteger('prepared_by')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->string('project_name')->nullable();
            $table->unsignedBigInteger('sales_person')->nullable();
            $table->string('payment_terms')->nullable();
            $table->date('promised_date')->nullable();
            $table->string('buyer_ref_no')->nullable();
            $table->decimal('net_amount',20,2)->default(0.0);
            $table->decimal('vat_amount',20,2)->default(0.0);
            $table->decimal('total_amount',20,2)->default(0.0);
            $table->text('in_word_taka')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('invoices');
    }
};

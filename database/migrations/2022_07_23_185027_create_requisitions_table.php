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
        Schema::create('requisitions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('visit_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->text('customer_address')->nullable();
            $table->text('party_name')->nullable();
            $table->bigInteger('invoice_no')->nullable();
            $table->bigInteger('sales_order_no')->nullable();
            $table->date('date')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('delivery_address')->nullable();
            $table->string('mobile')->nullable();
            $table->string('receiver_name')->nullable();
            $table->string('last_invoice_date')->nullable();
            $table->string('last_payment')->nullable();
            $table->bigInteger('amount')->nullable();
            $table->bigInteger('last_Amount')->nullable();
            $table->bigInteger('present_dues_amount')->nullable();
            $table->string('payment_mode')->nullable();
            $table->date('payment_date')->nullable();
            $table->bigInteger('commission_amount')->nullable();
            $table->string('any_special_note')->nullable();
            $table->string('amount_in_word')->nullable();
            $table->string('req_product_final_price')->nullable();
            $table->string('type')->nullable()->comment('spear_parts,product,inhouse_product');
            $table->string('status')->nullable()->comment('temp,pending,reviewed,approved');
            $table->unsignedBigInteger('pending_at')->nullable();
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
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
        Schema::dropIfExists('requisitions');
    }
};

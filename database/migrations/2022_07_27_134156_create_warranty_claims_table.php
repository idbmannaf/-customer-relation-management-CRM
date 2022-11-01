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
        Schema::create('warranty_claims', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('visit_plan_id')->nullable();
            $table->unsignedBigInteger('visit_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->unsignedBigInteger('requisition_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('product_name')->nullable();
            $table->bigInteger('unit_price')->default(0);
            $table->bigInteger('quantity')->default(0);
            $table->bigInteger('total_price')->default(0);
            $table->string('customer_address')->nullable();
            $table->string('complain_no')->nullable();
            $table->date('complain_date')->nullable();
            $table->date('sale_date')->nullable();
            $table->string('invoice')->nullable();
            $table->string('warranty_period')->nullable();
            $table->string('warranty_provide_for')->nullable();
            $table->unsignedBigInteger('item_id')->nullable();
            $table->string('old_product_serial_number')->nullable();
            $table->unsignedBigInteger('reported_eng_name')->nullable();
            $table->string('eng_mobile_number')->nullable();
            $table->string('comment')->nullable();
            $table->string('before_charge_v')->nullable();
            $table->string('after_charge_v')->nullable();
            $table->string('testing_load_with')->nullable();
            $table->string('backup_time')->nullable();
            $table->string('for_ups_and_others')->nullable();
            $table->bigInteger('current_due')->nullable();
            $table->string('last_payment')->nullable();
            $table->string('solution')->nullable();
            $table->date('date')->nullable();
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('prepared_by')->nullable(); // employee id
            $table->unsignedBigInteger('manager')->nullable(); // Service head
            $table->unsignedBigInteger('account_department')->nullable(); // admin
            $table->unsignedBigInteger('oparation_manager')->nullable(); // store head
            $table->string('status')->nullable(); //pending,confirmed,reviewed,approved
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
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
        Schema::dropIfExists('warranty_claims');
    }
};

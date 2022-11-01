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
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('visit_plan_id')->unsigned()->nullable();
            $table->bigInteger('customer_id')->unsigned()->nullable();
            $table->timestamp('date_time')->nullable();
            $table->text('purpose_of_visit')->nullable();
            $table->date('payment_collection_date')->nullable();
            $table->date('payment_maturity_date')->nullable();
            $table->text('achievement')->nullable();

            //Sales Part
            $table->text('sale_details')->nullable();
            $table->decimal('sale_amount',20,2)->default(0.00); // Customer Ledger balance - Collection Amount
            //Collection Part
            $table->decimal('collection_amount',20,2)->default(0.00); // Customer Ledger balance - Collection Amount
            $table->text('collection_details')->nullable();

            //History
            $table->decimal('previous_ledger_balance',20,2)->default(0.00);
            $table->decimal('current_ledger_balance',20,2)->default(0.00);
            $table->string('status')->nullable(); // Approved/rejected by Team Admin
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->bigInteger('addedBy_id')->unsigned()->nullable();
            $table->bigInteger('editedBy_id')->unsigned()->nullable();
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
        Schema::dropIfExists('visits');
    }
};

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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->bigInteger('employee_id')->unsigned()->nullable();
            $table->string('employee_name')->nullable();
            $table->string('customer_code')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('client_address')->nullable();
            $table->string('phone')->nullable();
            $table->bigInteger('area_id')->unsigned()->nullable();
            $table->string('customer_type')->nullable();
            $table->string('contact_person_name')->nullable();
            $table->string('degignation')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->decimal('ledger_balance',14,2)->default(0.00);
            $table->boolean('active')->default(0);
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
        Schema::dropIfExists('customers');
    }
};

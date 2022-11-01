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
        Schema::create('convayances', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('visit_plan_id')->unsigned()->nullable();
            $table->bigInteger('visit_id')->unsigned()->nullable();
            $table->bigInteger('employee_id')->unsigned()->nullable();
            $table->bigInteger('customer_id')->unsigned()->nullable();
            $table->decimal('total_amount', 20, 2)->default(0.00);
            $table->string('status')->nullable(); // Approved/rejected/Temp by Team Admin
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
        Schema::dropIfExists('convayances');
    }
};

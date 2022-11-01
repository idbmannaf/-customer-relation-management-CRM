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
        Schema::create('visit_plans', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employee_id')->unsigned()->nullable();
            $table->bigInteger('customer_id')->unsigned()->nullable();
            $table->timestamp('date_time')->nullable();
            $table->text('purpose_of_visit')->nullable();
            $table->date('payment_collection_date')->nullable();
            $table->date('payment_maturity_date')->nullable();
            $table->string('type')->nullable();
            $table->string('status')->nullable(); //Pending/Approved
            $table->timestamp('team_admin_approved_at')->nullable();
            $table->timestamp('visited_at')->nullable();
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
        Schema::dropIfExists('visit_plans');
    }
};

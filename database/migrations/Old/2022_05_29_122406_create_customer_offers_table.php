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
        Schema::create('customer_offers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employee_id')->unsigned()->nullable();
            $table->bigInteger('customer_id')->unsigned()->nullable();
            $table->string('ref')->nullable();
            $table->date('date')->nullable();
            $table->text('to')->nullable();
            $table->text('subject')->nullable();
            $table->text('body')->nullable();
            $table->text('signature')->nullable();
            $table->text('terms_and_condition')->nullable();
            $table->bigInteger('addedBy_id')->unsigned()->nullable();
            $table->bigInteger('editedBy_id')->unsigned()->nullable();
            $table->string('status')->nullable();//employee: pending, Team-admin approved/rejected
            $table->timestamp('pending_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
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
        Schema::dropIfExists('customer_offers');
    }
};

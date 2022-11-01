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
        Schema::create('service_maintainces', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('visit_plan_id')->nullable();
            $table->unsignedBigInteger('visit_id')->nullable();
            $table->boolean('motherboard_checked')->default(0);
            $table->boolean('motherboard_cleaned')->default(0);
            $table->boolean('ac_dc_cable_checked')->default(0);
            $table->boolean('ac_dc_cable_cleaned')->default(0);
            $table->boolean('breaker_checked')->default(0);
            $table->boolean('breaker_cleaned')->default(0);
            $table->boolean('socket_checked')->default(0);
            $table->boolean('socket_cleaned')->default(0);
            $table->boolean('battert_ternubak_checked')->default(0);
            $table->boolean('battert_ternubak_cleaned')->default(0);
            $table->text('problem_one')->nullable();
            $table->text('problem_two')->nullable();
            $table->text('problem_three')->nullable();
            $table->text('problem_foure')->nullable();
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
        Schema::dropIfExists('service_maintainces');
    }
};

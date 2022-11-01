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
        Schema::create('global_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('visit_plan_id')->nullable();
            $table->unsignedBigInteger('visit_id')->nullable();
            $table->unsignedBigInteger('offer_id')->nullable();
            $table->string('name')->nullable();
            $table->string('type')->nullable();
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
        Schema::dropIfExists('global_images');
    }
};

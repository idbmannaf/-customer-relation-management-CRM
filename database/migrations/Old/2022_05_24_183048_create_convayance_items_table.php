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
        Schema::create('convayance_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('convayance_id')->unsigned()->nullable();
            $table->text('movement_details')->nullable();
            $table->decimal('amount',20,2)->default(0.00);
            $table->string('travel_mode')->nullable(); //Rickshaw/CNG/Bus/Motocycle
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
        Schema::dropIfExists('convayance_items');
    }
};

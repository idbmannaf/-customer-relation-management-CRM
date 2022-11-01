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
        Schema::create('calls', function (Blueprint $table) {
            $table->id();
            $table->timestamp('date_time')->nullable();
            $table->bigInteger('customer_id')->unsigned()->nullable();
            $table->bigInteger('customer_location_id')->unsigned()->nullable();
            $table->text('purpose_of_visit')->nullable();
            $table->text('admin_note')->nullable();
            $table->bigInteger('employee_id')->unsigned()->nullable();
            $table->timestamp('approved_at')->nullable();
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
        Schema::dropIfExists('calls');
    }
};

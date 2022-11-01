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
        Schema::create('customer_visits', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('team_id')->unsigned()->nullable();
            $table->bigInteger('team_member_user_id')->unsigned()->nullable();
            $table->string('purpose_of_visit')->nullable();
            $table->date('payment_collection_date')->nullable();
            $table->date('payment_maturity_date')->nullable();
            $table->date('date')->nullable();
            $table->string('time')->nullable();
            $table->bigInteger('customer_id')->unsigned()->nullable();
            $table->string('payment_confirmation')->nullable();
            $table->string('image')->nullable();
            $table->decimal('daile_sell',14,2)->nullable();
            $table->decimal('collection',14,2)->nullable();
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
        Schema::dropIfExists('customer_visits');
    }
};

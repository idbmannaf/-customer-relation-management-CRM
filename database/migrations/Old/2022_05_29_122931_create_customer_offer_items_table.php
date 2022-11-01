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
        Schema::create('customer_offer_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('customer_id')->unsigned()->nullable();
            $table->bigInteger('customer_offer_id')->unsigned()->nullable();
            $table->bigInteger('product_id')->unsigned()->nullable();
            $table->string('product_name')->nullable();
            $table->string('product_brand')->nullable();
            $table->string('product_type')->nullable();
            $table->string('product_origin')->nullable();
            $table->string('product_made_in')->nullable();
            $table->string('product_warranty')->nullable();
            $table->decimal('quantity',20,2)->default(0.00);
            $table->decimal('unit_price',20,2)->default(0.00);
            $table->decimal('total_price',20,2)->default(0.00);
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
        Schema::dropIfExists('customer_offer_items');
    }
};

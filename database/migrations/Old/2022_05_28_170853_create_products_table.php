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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->decimal('unit_price',20,2)->default(0.00);
            $table->bigInteger('category_id')->unsigned()->nullable();
            $table->bigInteger('brand_id')->unsigned()->nullable();
            $table->string('origin')->nullable();
            $table->string('made_in')->nullable();
            $table->text('terms_and_condition')->nullable();
            $table->string('warranty_type')->nullable();
            $table->bigInteger('warranty_value')->nullable();
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
        Schema::dropIfExists('products');
    }
};

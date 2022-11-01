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
        Schema::create('office_locations', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->bigInteger('company_id')->unsigned()->nullable();
            $table->bigInteger('division_id')->unsigned()->nullable();
            $table->bigInteger('district_id')->unsigned()->nullable();
            $table->bigInteger('thana_id')->unsigned()->nullable();
            $table->string('google_location')->nullable();
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->time('office_start_time')->nullable();
            $table->time('office_end_time')->nullable();
            $table->boolean('active')->default(0);
            $table->string('featured_image')->nullable();
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
        Schema::dropIfExists('office_locations');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNutritionalEquivalentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nutritional_equivalents', function (Blueprint $table) {
            $table->id();
            $table->json('breakfast')->nullable();
            $table->json('mid_lunch')->nullable();
            $table->json('lunch')->nullable();
            $table->json('mid_dinner')->nullable();
            $table->json('dinner')->nullable();
            $table->json('snack')->nullable();
            //FK - CITA CONTROL
            $table->unsignedBigInteger('appointment_id');
            $table->foreign('appointment_id')->references('id')->on('appointments');
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
        Schema::dropIfExists('nutritional_equivalents');
    }
}

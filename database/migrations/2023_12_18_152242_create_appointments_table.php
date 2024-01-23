<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->double('weight');
            $table->double('muscle');
            $table->double('fat');
            $table->double('average_fat');
            $table->double('cc');
            $table->double('viseral_fat');
            $table->longText('evolution');
            //FK - CLIENTE
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('users');
            //FK - NUTRICIONISTA
            $table->unsignedBigInteger('nutricionist_id');
            $table->foreign('nutricionist_id')->references('id')->on('users');
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
        Schema::dropIfExists('appointments');
    }
}

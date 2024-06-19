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
            $table->longText('video_call_url')->nullable();
            $table->string('start_time', 5)->nullable();
            $table->string('end_time', 5)->nullable();
            $table->longText('google_calendar')->nullable();
            $table->enum('status',['No Confirmada', 'Confirmada', 'Cancelada'])->default('No Confirmada');
            $table->longText('notes')->nullable();
            //FK - CLIENTE
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('users');
            //FK - NUTRICIONISTA
            $table->unsignedBigInteger('nutricionist_id');
            $table->foreign('nutricionist_id')->references('id')->on('users');
            //FK CONSULTORIO
            $table->unsignedBigInteger('consultive_room_id');
            $table->foreign('consultive_room_id')->references('id')->on('consultive_rooms');
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

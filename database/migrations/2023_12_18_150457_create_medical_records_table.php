<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicalRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->json('alergies')->nullable();
            $table->json('medicines')->nullable();
            $table->json('health_conditions')->nullable();
            $table->json('disorders')->nullable();
            $table->string('sleep_hours')->nullable();
            $table->longText('background')->nullable();
            $table->longText('consumption_record')->nullable();
            $table->float('height', 8, 2)->default(0.0);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('physical_activity_id')->nullable();
            $table->foreign('physical_activity_id')->references('id')->on('physical_activities');
            $table->unsignedBigInteger('objective_id')->nullable();
            $table->foreign('objective_id')->references('id')->on('objectives');
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
        Schema::dropIfExists('medical_records');
    }
}

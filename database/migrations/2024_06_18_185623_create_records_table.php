<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('records', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->double('weight')->nullable();
            $table->double('muscle')->nullable();
            $table->double('fat')->nullable();
            $table->double('average_fat')->nullable();
            $table->double('cc')->nullable();
            $table->double('viseral_fat')->nullable();
            $table->string('excercise')->nullable();
            $table->longText('notes_client')->nullable();
            $table->longText('notes_intern')->nullable();
            //FK - SUBCATEGORY
            $table->unsignedBigInteger('water_consumption_id');
            $table->foreign('water_consumption_id')->references('id')->on('subcategory');
            //FK - CLIENTE
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('records');
    }
};

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
        Schema::table('medical_records', function (Blueprint $table) {
            $table->integer('alcohol_consumption')->default(null);
            $table->integer('smoke')->default(null);
            $table->integer('water_consumption')->default(null);
            $table->integer('stress')->default(null);
            $table->integer('hours_of_sleep')->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medical_records', function (Blueprint $table) {
            $table->dropColumn('alcohol_consumption');
            $table->dropColumn('smoke');
            $table->dropColumn('water_consumption');
            $table->dropColumn('stress');
            $table->dropColumn('hours_of_sleep');
        });
    }
};

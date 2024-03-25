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
            $table->integer('alcohol_consumption')->default(null)->nullable();
            $table->integer('smoke')->default(null)->nullable();
            $table->integer('water_consumption')->default(null)->nullable();
            $table->integer('stress')->default(null)->nullable();
            $table->integer('hours_of_sleep')->default(null)->nullable();
            $table->integer('physical_activity')->default(null)->nullable();
            $table->integer('objective')->default(null)->nullable();
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

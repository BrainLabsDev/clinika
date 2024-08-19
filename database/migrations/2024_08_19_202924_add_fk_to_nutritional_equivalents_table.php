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
        Schema::table('nutritional_equivalents', function (Blueprint $table) {
            $table->dropForeign('appointment_id');
            $table->dropIndex('appointment_id');
            $table->dropColumn('appointment_id');
            $table->unsignedBigInteger('record_id');
            $table->foreign('record_id')->references('id')->on('records');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nutritional_equivalents', function (Blueprint $table) {
            $table->dropForeign('record_id');
            $table->dropIndex('record_id');
            $table->dropColumn('record_id');
            $table->unsignedBigInteger('appointment_id');
            $table->foreign('appointment_id')->references('id')->on('appointments');
        });
    }
};

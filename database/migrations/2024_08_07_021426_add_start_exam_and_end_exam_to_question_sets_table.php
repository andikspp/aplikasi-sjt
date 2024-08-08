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
        Schema::table('question_sets', function (Blueprint $table) {
            $table->dateTime('start_exam')->nullable()->after('name');
            $table->dateTime('end_exam')->nullable()->after('start_exam');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('question_sets', function (Blueprint $table) {
            $table->dropColumn(['start_exam', 'end_exam']);
        });
    }
};

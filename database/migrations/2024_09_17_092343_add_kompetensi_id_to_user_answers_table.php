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
        Schema::table('user_answers', function (Blueprint $table) {
            $table->unsignedBigInteger('kompetensi_id')->nullable()->after('answer_id');
            $table->foreign('kompetensi_id')->references('id')->on('kompetensi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_answers', function (Blueprint $table) {
            $table->dropForeign(['kompetensi_id']);
            $table->dropColumn('kompetensi_id');
        });
    }
};

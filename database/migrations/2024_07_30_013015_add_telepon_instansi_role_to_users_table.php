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
        Schema::table('users', function (Blueprint $table) {
            $table->string('telepon')->nullable();
            $table->string('instansi')->nullable();
            $table->enum('role', ['Guru', 'Kepala Sekolah'])->default('Guru');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('telepon');
            $table->dropColumn('instansi');
            $table->dropColumn('role');
        });
    }
};

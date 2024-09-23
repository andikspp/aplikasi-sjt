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
        Schema::create('indikator', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->foreignId('kompetensi_id')->constrained('kompetensi')->onDelete('cascade');
            $table->enum('role', ['Kepala Sekolah', 'Guru']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indikator');
    }
};

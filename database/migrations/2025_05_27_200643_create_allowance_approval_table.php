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
        Schema::create('allowance_approval', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('allowance_id');
            $table->unsignedBigInteger('admin_id');
            $table->enum('status', ['approved', 'rejected']);
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->foreign('allowance_id')->references('id')->on('allowance')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allowance_approval');
    }
};

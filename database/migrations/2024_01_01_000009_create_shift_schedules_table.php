<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shift_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('shift_id')->constrained('shifts')->onDelete('cascade');
            $table->date('tanggal');
            $table->timestamps();

            $table->unique(['user_id', 'tanggal']); // 1 satpam hanya 1 shift per hari
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shift_schedules');
    }
};

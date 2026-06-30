<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->string('name');              // Misal: Shift Pagi, Shift Siang, Shift Malam
            $table->time('jam_masuk_start');      // Jam mulai boleh absen masuk
            $table->time('jam_masuk_end');        // Jam akhir boleh absen masuk
            $table->time('batas_terlambat');      // Batas jam dianggap terlambat
            $table->time('jam_pulang');           // Jam pulang
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};

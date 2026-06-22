<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('office_latitude', 10, 7);
            $table->decimal('office_longitude', 10, 7);
            $table->string('office_name')->default('PTUN Bandar Lampung');
            $table->text('office_address')->nullable();
            $table->integer('max_radius_meters')->default(50);
            $table->time('jam_masuk_start')->default('08:00:00');
            $table->time('jam_masuk_end')->default('08:15:00');
            $table->time('jam_pulang')->default('16:00:00');
            $table->time('batas_terlambat')->default('08:15:00');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_settings');
    }
};

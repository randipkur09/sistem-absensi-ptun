<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->after('id')->constrained('roles')->onDelete('restrict');
            $table->enum('employee_type', ['outsourcing', 'magang'])->after('password')->nullable();
            $table->string('phone', 20)->after('employee_type')->nullable();
            $table->text('address')->after('phone')->nullable();
            $table->string('photo')->after('address')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->after('photo')->default('aktif');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn(['role_id', 'employee_type', 'phone', 'address', 'photo', 'status']);
        });
    }
};

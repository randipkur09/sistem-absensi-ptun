<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AutoCleanupAttendancePhotos
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    /**
     * Jalankan cleanup setelah response dikirim ke browser.
     * Menggunakan cache agar hanya berjalan sekali per minggu.
     */
    public function terminate(Request $request, Response $response): void
    {
        // Cek apakah sudah pernah cleanup dalam 7 hari terakhir
        if (Cache::has('attendance_photos_last_cleanup')) {
            return;
        }

        try {
            // Set cache dulu untuk mencegah eksekusi paralel
            Cache::put('attendance_photos_last_cleanup', now(), now()->addDays(7));

            // Jalankan cleanup command
            Artisan::call('attendance:cleanup-photos');

            Log::info('Auto cleanup foto absensi berhasil dijalankan.');
        } catch (\Exception $e) {
            Log::error('Auto cleanup foto absensi gagal: ' . $e->getMessage());
            // Hapus cache agar bisa dicoba lagi di request berikutnya
            Cache::forget('attendance_photos_last_cleanup');
        }
    }
}

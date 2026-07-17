<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CleanupAttendancePhotos extends Command
{
    protected $signature = 'attendance:cleanup-photos
                            {--days=7 : Hapus foto yang lebih lama dari jumlah hari ini (default: 7)}
                            {--dry-run : Tampilkan foto yang akan dihapus tanpa benar-benar menghapus}';

    protected $description = 'Hapus foto absensi yang lebih lama dari 7 hari untuk menghemat penyimpanan';

    public function handle()
    {
        $days = (int) $this->option('days');
        $dryRun = $this->option('dry-run');
        $cutoffDate = Carbon::today()->subDays($days);

        $this->info("Menghapus foto absensi yang lebih lama dari {$days} hari (sebelum {$cutoffDate->format('d/m/Y')})...");

        if ($dryRun) {
            $this->warn('Mode DRY RUN — tidak ada file yang akan dihapus.');
        }

        // Ambil semua record absensi yang memiliki foto dan tanggalnya lebih lama dari cutoff
        $attendances = Attendance::where('tanggal', '<', $cutoffDate)
            ->where(function ($query) {
                $query->whereNotNull('foto_masuk')
                      ->orWhereNotNull('foto_pulang');
            })
            ->get();

        $deletedCount = 0;
        $failedCount = 0;
        $disk = Storage::disk('attendance_photos');

        foreach ($attendances as $attendance) {
            // Hapus foto masuk
            if ($attendance->foto_masuk) {
                if ($dryRun) {
                    $this->line("  [DRY RUN] Akan hapus: {$attendance->foto_masuk}");
                } else {
                    if ($disk->exists($attendance->foto_masuk)) {
                        $disk->delete($attendance->foto_masuk);
                        $deletedCount++;
                    }
                }
                // Set null di database agar tidak coba akses file yang sudah tidak ada
                if (! $dryRun) {
                    $attendance->update(['foto_masuk' => null]);
                }
            }

            // Hapus foto pulang
            if ($attendance->foto_pulang) {
                if ($dryRun) {
                    $this->line("  [DRY RUN] Akan hapus: {$attendance->foto_pulang}");
                } else {
                    if ($disk->exists($attendance->foto_pulang)) {
                        $disk->delete($attendance->foto_pulang);
                        $deletedCount++;
                    }
                }
                if (! $dryRun) {
                    $attendance->update(['foto_pulang' => null]);
                }
            }
        }

        if ($dryRun) {
            $totalPhotos = $attendances->filter(fn ($a) => $a->foto_masuk)->count()
                         + $attendances->filter(fn ($a) => $a->foto_pulang)->count();
            $this->info("Total foto yang akan dihapus: {$totalPhotos}");
        } else {
            $this->info("Selesai! {$deletedCount} foto berhasil dihapus.");

            if ($failedCount > 0) {
                $this->warn("{$failedCount} foto gagal dihapus.");
            }

            Log::info("Cleanup foto absensi: {$deletedCount} foto dihapus (cutoff: {$cutoffDate->format('Y-m-d')}).");
        }

        return Command::SUCCESS;
    }
}

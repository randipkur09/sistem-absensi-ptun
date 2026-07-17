<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Generate record alfa setiap hari jam 23:55 untuk pegawai yang tidak absen hari ini
        $schedule->command('attendance:generate-alfa')->dailyAt('23:55');

        // Hapus foto absensi yang lebih dari 7 hari setiap hari Minggu jam 01:00
        $schedule->command('attendance:cleanup-photos')->weeklyOn(0, '01:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateAlfa extends Command
{
    protected $signature = 'attendance:generate-alfa
                            {--date= : Tanggal spesifik (format: Y-m-d). Default: kemarin}
                            {--from= : Tanggal mulai untuk generate range}
                            {--to= : Tanggal akhir untuk generate range}';

    protected $description = 'Generate record alfa untuk pegawai yang tidak absen pada hari kerja';

    public function handle()
    {
        if ($this->option('from') && $this->option('to')) {
            $startDate = Carbon::parse($this->option('from'));
            $endDate = Carbon::parse($this->option('to'));
        } elseif ($this->option('date')) {
            $startDate = Carbon::parse($this->option('date'));
            $endDate = $startDate->copy();
        } else {
            // Default: kemarin
            $startDate = Carbon::yesterday();
            $endDate = Carbon::yesterday();
        }

        $count = self::generateAlfaRecords($startDate, $endDate);

        $this->info("Berhasil membuat {$count} record alfa dari {$startDate->format('d/m/Y')} s/d {$endDate->format('d/m/Y')}.");

        return Command::SUCCESS;
    }

    /**
     * Generate record alfa untuk pegawai yang tidak absen pada hari kerja.
     * Bisa dipanggil dari command maupun dari controller.
     *
     * @param  int|null  $userId  Filter pegawai tertentu (opsional)
     * @return int Jumlah record alfa yang dibuat
     */
    public static function generateAlfaRecords(Carbon $startDate, Carbon $endDate, ?int $userId = null): int
    {
        $today = Carbon::today();
        $count = 0;

        // Ambil pegawai aktif beserta relasinya untuk cek tanggal kontrak/magang
        $employeesQuery = User::with(['outsourcingEmployee', 'internshipParticipant', 'shiftSchedules' => function ($q) use ($startDate, $endDate) {
            $q->whereBetween('tanggal', [$startDate, $endDate]);
        }])
            ->whereHas('role', fn ($q) => $q->where('name', 'pegawai'))
            ->where('status', 'aktif');

        if ($userId) {
            $employeesQuery->where('id', $userId);
        }

        $employees = $employeesQuery->get();

        // Loop setiap hari kerja dalam rentang, hanya sampai kemarin (hari ini belum selesai)
        $current = $startDate->copy()->startOfDay();
        $end = $endDate->copy()->endOfDay();

        while ($current->lte($end) && $current->lt($today)) {
            $tanggal = $current->toDateString();
            $isWeekend = $current->isWeekend();

            foreach ($employees as $employee) {
                $isSatpam = $employee->isSatpam();

                // Jika bukan satpam dan hari ini weekend, skip
                if (! $isSatpam && $isWeekend) {
                    continue;
                }

                // Cek masa aktif pegawai berdasarkan tipe
                $isActiveOnDate = true;
                if ($employee->isOutsourcing() && $employee->outsourcingEmployee) {
                    $startContract = $employee->outsourcingEmployee->contract_start;
                    $endContract = $employee->outsourcingEmployee->contract_end;
                    if ($startContract && $current->lt($startContract->copy()->startOfDay())) {
                        $isActiveOnDate = false;
                    }
                    if ($endContract && $current->gt($endContract->copy()->endOfDay())) {
                        $isActiveOnDate = false;
                    }
                } elseif ($employee->isMagang() && $employee->internshipParticipant) {
                    $startInternship = $employee->internshipParticipant->start_date;
                    $endInternship = $employee->internshipParticipant->end_date;
                    if ($startInternship && $current->lt($startInternship->copy()->startOfDay())) {
                        $isActiveOnDate = false;
                    }
                    if ($endInternship && $current->gt($endInternship->copy()->endOfDay())) {
                        $isActiveOnDate = false;
                    }
                }

                if (! $isActiveOnDate) {
                    continue;
                }

                // Jika Satpam, pastikan dia punya jadwal shift di hari ini
                if ($isSatpam) {
                    $hasSchedule = $employee->shiftSchedules->contains('tanggal', $current->copy()->startOfDay());
                    if (! $hasSchedule) {
                        continue; // Libur, tidak perlu alfa
                    }
                }

                // Cek apakah sudah ada record absensi untuk pegawai ini di tanggal ini
                $exists = Attendance::where('user_id', $employee->id)
                    ->where('tanggal', $tanggal)
                    ->exists();

                if (! $exists) {
                    Attendance::create([
                        'user_id' => $employee->id,
                        'tanggal' => $tanggal,
                        'status' => 'alfa',
                        'keterangan' => 'Tidak hadir tanpa keterangan',
                    ]);
                    $count++;
                }
            }

            $current->addDay();
        }

        return $count;
    }
}

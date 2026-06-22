<?php

namespace App\Exports;

use App\Models\Attendance;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;
    protected $userId;

    public function __construct($startDate, $endDate, $userId = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->userId = $userId;
    }

    public function collection()
    {
        $query = Attendance::with('user')
            ->whereBetween('tanggal', [$this->startDate, $this->endDate]);

        if ($this->userId) {
            $query->where('user_id', $this->userId);
        }

        return $query->orderBy('tanggal', 'asc')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama',
            'Tipe',
            'Tanggal',
            'Jam Masuk',
            'Jam Pulang',
            'Status',
            'Jarak Masuk (m)',
            'Jarak Pulang (m)',
            'Keterangan',
        ];
    }

    public function map($attendance): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $attendance->user->name ?? '-',
            ucfirst($attendance->user->employee_type ?? '-'),
            $attendance->tanggal->format('d/m/Y'),
            $attendance->jam_masuk ?? '-',
            $attendance->jam_pulang ?? '-',
            ucfirst($attendance->status),
            $attendance->jarak_masuk ?? '-',
            $attendance->jarak_pulang ?? '-',
            $attendance->keterangan ?? '-',
        ];
    }

    public function title(): string
    {
        return 'Laporan Absensi';
    }
}

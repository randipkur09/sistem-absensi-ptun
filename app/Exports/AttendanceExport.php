<?php

namespace App\Exports;

use App\Models\Attendance;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class AttendanceExport extends DefaultValueBinder implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithCustomValueBinder
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

    public function bindValue(Cell $cell, $value)
    {
        if (is_string($value)) {
            // Jam Masuk / Jam Pulang format (e.g., "08:21:20")
            if (preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $value)) {
                $cell->setValueExplicit($value, DataType::TYPE_STRING);
                return true;
            }
            // Tanggal format (e.g., "22/06/2026")
            if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $value)) {
                $cell->setValueExplicit($value, DataType::TYPE_STRING);
                return true;
            }
        }

        return parent::bindValue($cell, $value);
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
            $attendance->tanggal ? $attendance->tanggal->format('d/m/Y') : '-',
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

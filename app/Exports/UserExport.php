<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UserExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
{
    protected $type;

    public function __construct($type = null)
    {
        $this->type = $type;
    }

    public function collection()
    {
        $query = User::with(['outsourcingEmployee', 'internshipParticipant'])
            ->whereHas('role', fn ($q) => $q->where('name', 'pegawai'));

        if ($this->type) {
            $query->where('employee_type', $this->type);
        }

        return $query->orderBy('name')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama',
            'Username',
            'Tipe',
            'Telepon',
            'Alamat',
            'Status',
            'Perusahaan/Institusi',
            'Jabatan/Jurusan',
            'Tanggal Mulai',
            'Tanggal Selesai',
        ];
    }

    public function map($user): array
    {
        static $no = 0;
        $no++;

        $orgName = '-';
        $position = '-';
        $startDate = '-';
        $endDate = '-';

        if ($user->employee_type === 'outsourcing' && $user->outsourcingEmployee) {
            $orgName = $user->outsourcingEmployee->company_name;
            $position = $user->outsourcingEmployee->position;
            $startDate = $user->outsourcingEmployee->contract_start ? $user->outsourcingEmployee->contract_start->format('d/m/Y') : '-';
            $endDate = $user->outsourcingEmployee->contract_end ? $user->outsourcingEmployee->contract_end->format('d/m/Y') : '-';
        } elseif ($user->employee_type === 'magang' && $user->internshipParticipant) {
            $orgName = $user->internshipParticipant->institution;
            $position = $user->internshipParticipant->major;
            $startDate = $user->internshipParticipant->start_date ? $user->internshipParticipant->start_date->format('d/m/Y') : '-';
            $endDate = $user->internshipParticipant->end_date ? $user->internshipParticipant->end_date->format('d/m/Y') : '-';
        }

        return [
            $no,
            $user->name,
            $user->username,
            ucfirst($user->employee_type ?? '-'),
            $user->phone ?? '-',
            $user->address ?? '-',
            ucfirst($user->status),
            $orgName,
            $position,
            $startDate,
            $endDate,
        ];
    }
}

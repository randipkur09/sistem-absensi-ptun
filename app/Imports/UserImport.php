<?php

namespace App\Imports;

use App\Models\InternshipParticipant;
use App\Models\OutsourcingEmployee;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class UserImport implements ToModel, WithHeadingRow, WithValidation
{
    protected $type;

    public function __construct($type)
    {
        $this->type = $type;
    }

    public function model(array $row)
    {
        $pegawaiRole = Role::where('name', 'pegawai')->first();

        $user = User::create([
            'name' => $row['nama'],
            'email' => $row['email'],
            'password' => Hash::make($row['password'] ?? 'password123'),
            'role_id' => $pegawaiRole->id,
            'employee_type' => $this->type,
            'phone' => $row['telepon'] ?? null,
            'address' => $row['alamat'] ?? null,
            'status' => 'aktif',
        ]);

        if ($this->type === 'outsourcing') {
            OutsourcingEmployee::create([
                'user_id' => $user->id,
                'company_name' => $row['perusahaan'] ?? '-',
                'position' => $row['jabatan'] ?? '-',
                'contract_start' => $this->parseDate($row['tanggal_mulai'] ?? null, now()),
                'contract_end' => $this->parseDate($row['tanggal_selesai'] ?? null, now()->addYear()),
                'contract_number' => $row['nomor_kontrak'] ?? null,
            ]);
        } else {
            InternshipParticipant::create([
                'user_id' => $user->id,
                'institution' => $row['institusi'] ?? '-',
                'major' => $row['jurusan'] ?? '-',
                'start_date' => $this->parseDate($row['tanggal_mulai'] ?? null, now()),
                'end_date' => $this->parseDate($row['tanggal_selesai'] ?? null, now()->addMonths(6)),
                'supervisor' => $row['pembimbing'] ?? null,
            ]);
        }

        return $user;
    }

    public function rules(): array
    {
        return [
            'nama' => 'required|string',
            'email' => 'required|email|unique:users,email',
        ];
    }

    /**
     * Parse tanggal dari berbagai format Excel (dd/mm/yyyy, d/m/yyyy, yyyy-mm-dd, serial number).
     */
    protected function parseDate($value, $default = null): Carbon
    {
        if (empty($value)) {
            return $default instanceof Carbon ? $default : Carbon::parse($default);
        }

        // Jika berupa angka (Excel serial number)
        if (is_numeric($value)) {
            return Carbon::instance(Date::excelToDateTimeObject($value));
        }

        // Coba format dd/mm/yyyy atau d/m/yyyy
        $formats = ['d/m/Y', 'd-m-Y', 'Y-m-d', 'Y/m/d', 'd/m/y', 'd-m-y'];
        foreach ($formats as $format) {
            try {
                return Carbon::createFromFormat($format, $value);
            } catch (\Exception $e) {
                continue;
            }
        }

        // Fallback: coba Carbon::parse biasa
        try {
            return Carbon::parse($value);
        } catch (\Exception $e) {
            return $default instanceof Carbon ? $default : Carbon::parse($default);
        }
    }
}

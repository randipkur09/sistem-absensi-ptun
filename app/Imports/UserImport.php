<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Role;
use App\Models\OutsourcingEmployee;
use App\Models\InternshipParticipant;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

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
            'name'          => $row['nama'],
            'email'         => $row['email'],
            'password'      => Hash::make($row['password'] ?? 'password123'),
            'role_id'       => $pegawaiRole->id,
            'employee_type' => $this->type,
            'phone'         => $row['telepon'] ?? null,
            'address'       => $row['alamat'] ?? null,
            'status'        => 'aktif',
        ]);

        if ($this->type === 'outsourcing') {
            OutsourcingEmployee::create([
                'user_id'         => $user->id,
                'company_name'    => $row['perusahaan'] ?? '-',
                'position'        => $row['jabatan'] ?? '-',
                'contract_start'  => \Carbon\Carbon::parse($row['tanggal_mulai'] ?? now()),
                'contract_end'    => \Carbon\Carbon::parse($row['tanggal_selesai'] ?? now()->addYear()),
                'contract_number' => $row['nomor_kontrak'] ?? null,
            ]);
        } else {
            InternshipParticipant::create([
                'user_id'     => $user->id,
                'institution' => $row['institusi'] ?? '-',
                'major'       => $row['jurusan'] ?? '-',
                'start_date'  => \Carbon\Carbon::parse($row['tanggal_mulai'] ?? now()),
                'end_date'    => \Carbon\Carbon::parse($row['tanggal_selesai'] ?? now()->addMonths(6)),
                'supervisor'  => $row['pembimbing'] ?? null,
            ]);
        }

        return $user;
    }

    public function rules(): array
    {
        return [
            'nama'  => 'required|string',
            'email' => 'required|email|unique:users,email',
        ];
    }
}

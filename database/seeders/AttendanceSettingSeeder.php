<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AttendanceSetting;

class AttendanceSettingSeeder extends Seeder
{
    public function run(): void
    {
        AttendanceSetting::create([
            'office_latitude'   => '-5.4245573',
            'office_longitude'  => '105.2437446',
            'office_name'       => 'PTUN Bandar Lampung',
            'office_address'    => 'Jl. Pangeran Emir M. Noer No.73, Durian Payung, Kec. Tanjung Karang Pusat, Kota Bandar Lampung, Lampung',
            'max_radius_meters' => 50,
            'jam_masuk_start'   => '08:00:00',
            'jam_masuk_end'     => '08:15:00',
            'jam_pulang'        => '16:00:00',
            'batas_terlambat'   => '08:15:00',
        ]);
    }
}

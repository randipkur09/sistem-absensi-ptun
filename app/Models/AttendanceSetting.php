<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'office_latitude',
        'office_longitude',
        'office_name',
        'office_address',
        'max_radius_meters',
        'jam_masuk_start',
        'jam_masuk_end',
        'jam_pulang',
        'batas_terlambat',
    ];

    protected $casts = [
        'office_latitude'   => 'decimal:7',
        'office_longitude'  => 'decimal:7',
        'max_radius_meters' => 'integer',
    ];

    /**
     * Get the current active setting (singleton pattern).
     */
    public static function current(): self
    {
        return self::first() ?? new self([
            'office_latitude'   => -5.3971,
            'office_longitude'  => 105.2668,
            'office_name'       => 'PTUN Bandar Lampung',
            'max_radius_meters' => 50,
            'jam_masuk_start'   => '08:00:00',
            'jam_masuk_end'     => '08:15:00',
            'jam_pulang'        => '16:00:00',
            'batas_terlambat'   => '08:15:00',
        ]);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tanggal',
        'jam_masuk',
        'jam_pulang',
        'latitude_masuk',
        'longitude_masuk',
        'latitude_pulang',
        'longitude_pulang',
        'jarak_masuk',
        'jarak_pulang',
        'foto_masuk',
        'foto_pulang',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'tanggal'          => 'date',
        'latitude_masuk'   => 'decimal:7',
        'longitude_masuk'  => 'decimal:7',
        'latitude_pulang'  => 'decimal:7',
        'longitude_pulang' => 'decimal:7',
        'jarak_masuk'      => 'decimal:2',
        'jarak_pulang'     => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFotoMasukUrlAttribute(): ?string
    {
        return $this->foto_masuk ? asset('storage/attendance-photos/' . $this->foto_masuk) : null;
    }

    public function getFotoPulangUrlAttribute(): ?string
    {
        return $this->foto_pulang ? asset('storage/attendance-photos/' . $this->foto_pulang) : null;
    }

    /**
     * Hitung jarak antara dua titik koordinat menggunakan rumus Haversine.
     * Mengembalikan jarak dalam meter.
     */
    public static function haversineDistance($lat1, $lon1, $lat2, $lon2): float
    {
        $earthRadius = 6371000; // meter

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}

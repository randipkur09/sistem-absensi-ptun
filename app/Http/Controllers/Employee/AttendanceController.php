<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $today = Carbon::today();
        $setting = AttendanceSetting::current();

        $todayAttendance = Attendance::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->first();

        $shiftForToday = $user->isSatpam() ? $user->getShiftForDate($today->format('Y-m-d')) : null;

        return view('employee.attendance.index', compact('todayAttendance', 'setting', 'shiftForToday'));
    }

    public function checkIn(Request $request)
    {
        $request->validate([
            'status_absensi' => 'required|in:hadir,sakit,izin',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'foto' => 'required|string', // base64 encoded image
            'keterangan' => 'nullable|string',
        ]);

        $statusAbsensi = $request->status_absensi;
        $latitude = $request->latitude ?? 0;
        $longitude = $request->longitude ?? 0;

        $user = auth()->user();
        $today = Carbon::today();
        $now = Carbon::now();
        $setting = AttendanceSetting::current();

        // Cek shift satpam untuk hari ini
        $shiftForToday = $user->isSatpam() ? $user->getShiftForDate($today->format('Y-m-d')) : null;

        // Check if already checked in today
        $existing = Attendance::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->first();

        if ($user->isSatpam() && ! $shiftForToday) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki jadwal shift hari ini (Libur).',
            ], 422);
        }

        if ($existing && $existing->jam_masuk) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan absensi masuk hari ini.',
            ], 422);
        }

        // Calculate distance using Haversine formula if status is hadir
        $distance = 0;
        if ($statusAbsensi === 'hadir') {
            $distance = Attendance::haversineDistance(
                $latitude,
                $longitude,
                $setting->office_latitude,
                $setting->office_longitude
            );

            // Validate distance
            if ($distance > $setting->max_radius_meters) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda berada di luar radius kantor. Jarak Anda: '.round($distance, 2).' meter dari kantor. Maksimal: '.$setting->max_radius_meters.' meter.',
                    'distance' => round($distance, 2),
                ], 422);
            }
        }

        // Save photo from base64
        $fotoName = $this->saveBase64Photo($request->foto, 'masuk', $user->id);

        // Determine status
        $status = $statusAbsensi;
        if ($status === 'hadir') {
            $batasTerlambat = $shiftForToday
                ? Carbon::parse($shiftForToday->batas_terlambat)
                : Carbon::parse($setting->batas_terlambat);

            if ($now->format('H:i:s') > $batasTerlambat->format('H:i:s')) {
                $status = 'terlambat';
            }
        }

        // Create or update attendance
        $attendance = Attendance::updateOrCreate(
            [
                'user_id' => $user->id,
                'tanggal' => $today,
            ],
            [
                'shift_id' => $shiftForToday?->id,
                'jam_masuk' => $now->format('H:i:s'),
                'latitude_masuk' => $latitude,
                'longitude_masuk' => $longitude,
                'jarak_masuk' => round($distance, 2),
                'foto_masuk' => $fotoName,
                'status' => $status,
                'keterangan' => $request->keterangan,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Absensi masuk berhasil dicatat! Status: '.ucfirst($status),
            'data' => $attendance,
            'distance' => round($distance, 2),
        ]);
    }

    public function checkOut(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'foto' => 'required|string', // base64 encoded image
        ]);

        $user = auth()->user();
        $today = Carbon::today();
        $now = Carbon::now();
        $setting = AttendanceSetting::current();

        // Check if checked in today
        $attendance = Attendance::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->first();

        if (! $attendance || ! $attendance->jam_masuk) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum melakukan absensi masuk hari ini.',
            ], 422);
        }

        if ($attendance->jam_pulang) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan absensi pulang hari ini.',
            ], 422);
        }

        // Calculate distance
        $distance = Attendance::haversineDistance(
            $request->latitude,
            $request->longitude,
            $setting->office_latitude,
            $setting->office_longitude
        );

        // Validate distance
        if ($distance > $setting->max_radius_meters) {
            return response()->json([
                'success' => false,
                'message' => 'Anda berada di luar radius kantor. Jarak Anda: '.round($distance, 2).' meter dari kantor. Maksimal: '.$setting->max_radius_meters.' meter.',
                'distance' => round($distance, 2),
            ], 422);
        }

        // Save photo
        $fotoName = $this->saveBase64Photo($request->foto, 'pulang', $user->id);

        $attendance->update([
            'jam_pulang' => $now->format('H:i:s'),
            'latitude_pulang' => $request->latitude,
            'longitude_pulang' => $request->longitude,
            'jarak_pulang' => round($distance, 2),
            'foto_pulang' => $fotoName,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Absensi pulang berhasil dicatat!',
            'data' => $attendance->fresh(),
            'distance' => round($distance, 2),
        ]);
    }

    protected function saveBase64Photo(string $base64, string $type, int $userId): string
    {
        $image = str_replace('data:image/png;base64,', '', $base64);
        $image = str_replace('data:image/jpeg;base64,', '', $image);
        $image = str_replace('data:image/webp;base64,', '', $image);
        $image = str_replace(' ', '+', $image);

        $imageName = $type.'_'.$userId.'_'.Carbon::now()->format('Y-m-d_H-i-s').'.png';

        Storage::disk('attendance_photos')->put($imageName, base64_decode($image));

        return $imageName;
    }
}

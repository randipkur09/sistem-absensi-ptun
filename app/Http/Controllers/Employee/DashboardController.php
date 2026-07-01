<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceSetting;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $today = Carbon::today();
        $setting = AttendanceSetting::current();

        // Absensi hari ini
        $todayAttendance = Attendance::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->first();

        // Statistik bulan ini
        $currentMonth = Carbon::now();
        $monthlyStats = Attendance::where('user_id', $user->id)
            ->whereMonth('tanggal', $currentMonth->month)
            ->whereYear('tanggal', $currentMonth->year)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // Riwayat 7 hari terakhir
        $recentAttendances = Attendance::where('user_id', $user->id)
            ->orderBy('tanggal', 'desc')
            ->take(7)
            ->get();


        return view('employee.dashboard', compact(
            'todayAttendance',
            'monthlyStats',
            'recentAttendances',
            'setting'
        ));
    }
}

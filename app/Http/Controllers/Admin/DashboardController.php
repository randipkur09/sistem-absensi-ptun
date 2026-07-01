<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Carbon\Carbon;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $currentMonth = Carbon::now();

        // Statistik pengguna
        $totalUsers = User::whereHas('role', fn ($q) => $q->where('name', 'pegawai'))->count();
        $totalOutsourcing = User::where('employee_type', 'outsourcing')->where('status', 'aktif')->count();
        $totalMagang = User::where('employee_type', 'magang')->where('status', 'aktif')->count();

        // Statistik absensi hari ini
        $todayAttendances = Attendance::where('tanggal', $today)->get();
        $hadirToday = $todayAttendances->whereIn('status', ['hadir', 'terlambat'])->count();
        $terlambatToday = $todayAttendances->where('status', 'terlambat')->count();
        $izinToday = Attendance::where('tanggal', $today)->where('status', 'izin')->count();
        $sakitToday = Attendance::where('tanggal', $today)->where('status', 'sakit')->count();

        // Daftar absensi terbaru
        $recentAttendances = Attendance::with('user')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();


        return view('admin.dashboard', compact(
            'totalUsers',
            'totalOutsourcing',
            'totalMagang',
            'hadirToday',
            'terlambatToday',
            'izinToday',
            'sakitToday',
            'recentAttendances'
        ));
    }
}

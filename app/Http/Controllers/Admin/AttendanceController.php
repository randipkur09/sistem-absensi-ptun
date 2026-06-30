<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with('user');

        if ($request->filled('tanggal')) {
            $query->where('tanggal', $request->tanggal);
        } else {
            $query->where('tanggal', Carbon::today());
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query->orderBy('tanggal', 'desc')
            ->orderBy('jam_masuk', 'desc')
            ->paginate(20);

        $users = User::whereHas('role', fn ($q) => $q->where('name', 'pegawai'))
            ->where('status', 'aktif')
            ->orderBy('name')
            ->get();

        return view('admin.attendance.index', compact('attendances', 'users'));
    }

    public function show(Attendance $attendance)
    {
        $attendance->load('user');

        return view('admin.attendance.show', compact('attendance'));
    }
}

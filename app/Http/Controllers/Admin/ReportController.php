<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use App\Exports\AttendanceExport;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with('user');

        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->start_date)
            : Carbon::now()->startOfMonth();

        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date)
            : Carbon::now()->endOfMonth();

        $query->whereBetween('tanggal', [$startDate, $endDate]);

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $attendances = $query->orderBy('tanggal', 'desc')
            ->orderBy('jam_masuk', 'desc')
            ->paginate(20);

        // Summary statistics
        $allAttendances = Attendance::whereBetween('tanggal', [$startDate, $endDate]);
        if ($request->filled('user_id')) {
            $allAttendances->where('user_id', $request->user_id);
        }
        $summary = $allAttendances->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $users = User::whereHas('role', fn($q) => $q->where('name', 'pegawai'))
            ->where('status', 'aktif')
            ->orderBy('name')
            ->get();

        return view('admin.reports.index', compact(
            'attendances', 'users', 'startDate', 'endDate', 'summary'
        ));
    }

    public function exportPdf(Request $request)
    {
        $query = Attendance::with('user');

        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->start_date)
            : Carbon::now()->startOfMonth();

        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date)
            : Carbon::now()->endOfMonth();

        $query->whereBetween('tanggal', [$startDate, $endDate]);

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $attendances = $query->orderBy('tanggal', 'asc')->get();

        $pdf = Pdf::loadView('admin.reports.pdf', compact('attendances', 'startDate', 'endDate'));
        $pdf->setPaper('A4', 'landscape');

        $filename = 'Laporan_Absensi_' . $startDate->format('d-m-Y') . '_sd_' . $endDate->format('d-m-Y') . '.pdf';

        return $pdf->download($filename);
    }

    public function exportExcel(Request $request)
    {
        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->start_date)
            : Carbon::now()->startOfMonth();

        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date)
            : Carbon::now()->endOfMonth();

        $userId = $request->user_id;

        $filename = 'Laporan_Absensi_' . $startDate->format('d-m-Y') . '_sd_' . $endDate->format('d-m-Y') . '.xlsx';

        return Excel::download(new AttendanceExport($startDate, $endDate, $userId), $filename);
    }
}

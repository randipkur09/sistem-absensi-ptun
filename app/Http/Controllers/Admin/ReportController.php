<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use App\Exports\AttendanceExport;
use App\Console\Commands\GenerateAlfa;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->start_date)
            : Carbon::now()->startOfMonth();

        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date)
            : Carbon::now()->endOfMonth();

        $userId = $request->filled('user_id') ? $request->user_id : null;
        $employeeType = $request->filled('employee_type') ? $request->employee_type : null;

        // Generate record alfa otomatis untuk hari kerja yang belum ada absensi
        GenerateAlfa::generateAlfaRecords($startDate, $endDate, $userId);

        $query = Attendance::with('user')
            ->whereBetween('tanggal', [$startDate, $endDate]);

        if ($userId) {
            $query->where('user_id', $userId);
        } elseif ($employeeType) {
            $query->whereHas('user', function($q) use ($employeeType) {
                $q->where('employee_type', $employeeType);
            });
        }

        $attendances = $query->orderBy('tanggal', 'desc')
            ->orderBy('jam_masuk', 'desc')
            ->paginate(20);

        // Summary statistics — alfa sudah jadi record di database
        $summaryQuery = Attendance::whereBetween('tanggal', [$startDate, $endDate]);
        if ($userId) {
            $summaryQuery->where('user_id', $userId);
        } elseif ($employeeType) {
            $summaryQuery->whereHas('user', function($q) use ($employeeType) {
                $q->where('employee_type', $employeeType);
            });
        }
        $summary = $summaryQuery->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $usersQuery = User::whereHas('role', fn($q) => $q->where('name', 'pegawai'))
            ->where('status', 'aktif');
            
        if ($employeeType) {
            $usersQuery->where('employee_type', $employeeType);
        }
        
        $users = $usersQuery->orderBy('name')->get();

        return view('admin.reports.index', compact(
            'attendances', 'users', 'startDate', 'endDate', 'summary'
        ));
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->start_date)
            : Carbon::now()->startOfMonth();

        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date)
            : Carbon::now()->endOfMonth();

        $userId = $request->filled('user_id') ? $request->user_id : null;
        $employeeType = $request->filled('employee_type') ? $request->employee_type : null;

        // Generate record alfa sebelum export
        GenerateAlfa::generateAlfaRecords($startDate, $endDate, $userId);

        $query = Attendance::with('user')
            ->whereBetween('tanggal', [$startDate, $endDate]);

        if ($userId) {
            $query->where('user_id', $userId);
        } elseif ($employeeType) {
            $query->whereHas('user', function($q) use ($employeeType) {
                $q->where('employee_type', $employeeType);
            });
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

        $userId = $request->filled('user_id') ? $request->user_id : null;
        $employeeType = $request->filled('employee_type') ? $request->employee_type : null;

        // Generate record alfa sebelum export
        GenerateAlfa::generateAlfaRecords($startDate, $endDate, $userId);

        $filename = 'Laporan_Absensi_' . $startDate->format('d-m-Y') . '_sd_' . $endDate->format('d-m-Y') . '.xlsx';

        return Excel::download(new AttendanceExport($startDate, $endDate, $userId, $employeeType), $filename);
    }
}

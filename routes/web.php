<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\OutsourcingController;
use App\Http\Controllers\Admin\InternshipController;
use App\Http\Controllers\Admin\AttendanceController as AdminAttendance;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\PermissionController as AdminPermission;
use App\Http\Controllers\Employee\DashboardController as EmployeeDashboard;
use App\Http\Controllers\Employee\AttendanceController as EmployeeAttendance;
use App\Http\Controllers\Employee\HistoryController;
use App\Http\Controllers\Employee\PermissionController as EmployeePermission;
use App\Exports\UserExport;
use App\Imports\UserImport;
use Maatwebsite\Excel\Facades\Excel;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ─── Auth Routes ──────────────────────────────────────────
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ─── Admin Routes ─────────────────────────────────────────
Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    // Outsourcing CRUD
    Route::resource('outsourcing', OutsourcingController::class);

    // Internship CRUD
    Route::resource('internship', InternshipController::class);

    // Attendance
    Route::get('/attendance', [AdminAttendance::class, 'index'])->name('attendance.index');
    Route::get('/attendance/{attendance}', [AdminAttendance::class, 'show'])->name('attendance.show');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export-pdf', [ReportController::class, 'exportPdf'])->name('reports.export-pdf');
    Route::get('/reports/export-excel', [ReportController::class, 'exportExcel'])->name('reports.export-excel');

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');

    // Permissions/Leave
    Route::get('/permissions', [AdminPermission::class, 'index'])->name('permissions.index');
    Route::put('/permissions/{permission}/approve', [AdminPermission::class, 'approve'])->name('permissions.approve');
    Route::put('/permissions/{permission}/reject', [AdminPermission::class, 'reject'])->name('permissions.reject');

    // Import/Export Users
    Route::get('/export-users/{type}', function ($type) {
        $filename = 'Data_' . ucfirst($type) . '_' . now()->format('d-m-Y') . '.xlsx';
        return Excel::download(new UserExport($type), $filename);
    })->name('export-users');

    Route::post('/import-users/{type}', function (\Illuminate\Http\Request $request, $type) {
        $request->validate(['file' => 'required|mimes:xlsx,xls,csv|max:5120']);
        Excel::import(new UserImport($type), $request->file('file'));
        return back()->with('success', 'Data berhasil diimport.');
    })->name('import-users');
});

// ─── Employee Routes ──────────────────────────────────────
Route::prefix('employee')->middleware(['auth', 'role:pegawai'])->name('employee.')->group(function () {

    Route::get('/dashboard', [EmployeeDashboard::class, 'index'])->name('dashboard');

    // Attendance
    Route::get('/attendance', [EmployeeAttendance::class, 'index'])->name('attendance.index');
    Route::post('/attendance/check-in', [EmployeeAttendance::class, 'checkIn'])->name('attendance.check-in');
    Route::post('/attendance/check-out', [EmployeeAttendance::class, 'checkOut'])->name('attendance.check-out');

    // History
    Route::get('/history', [HistoryController::class, 'index'])->name('history.index');

    // Permissions/Leave
    Route::get('/permissions', [EmployeePermission::class, 'index'])->name('permissions.index');
    Route::get('/permissions/create', [EmployeePermission::class, 'create'])->name('permissions.create');
    Route::post('/permissions', [EmployeePermission::class, 'store'])->name('permissions.store');
});

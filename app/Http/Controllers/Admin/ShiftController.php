<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use App\Models\ShiftSchedule;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ShiftController extends Controller
{
    /**
     * Halaman utama: Master Shift + Jadwal Shift Satpam
     */
    public function index(Request $request)
    {
        $shifts = Shift::orderBy('name')->get();

        // Ambil semua satpam outsourcing aktif
        $satpams = User::whereHas('role', fn($q) => $q->where('name', 'pegawai'))
            ->where('employee_type', 'outsourcing')
            ->where('status', 'aktif')
            ->whereHas('outsourcingEmployee', function ($q) {
                $q->whereRaw('LOWER(position) = ?', ['satpam']);
            })
            ->with('outsourcingEmployee')
            ->orderBy('name')
            ->get();

        // Filter minggu untuk jadwal
        $weekStart = $request->filled('week_start')
            ? Carbon::parse($request->week_start)->startOfWeek(Carbon::MONDAY)
            : Carbon::now()->startOfWeek(Carbon::MONDAY);
        $weekEnd = $weekStart->copy()->endOfWeek(Carbon::SUNDAY);

        // Ambil jadwal shift untuk minggu yang dipilih
        $schedules = ShiftSchedule::with(['user', 'shift'])
            ->whereBetween('tanggal', [$weekStart, $weekEnd])
            ->whereIn('user_id', $satpams->pluck('id'))
            ->get()
            ->groupBy(function ($item) {
                return $item->user_id . '_' . $item->tanggal->format('Y-m-d');
            });

        // Buat array hari dalam minggu
        $days = [];
        $current = $weekStart->copy();
        while ($current->lte($weekEnd)) {
            $days[] = $current->copy();
            $current->addDay();
        }

        return view('admin.shifts.index', compact(
            'shifts', 'satpams', 'schedules', 'days', 'weekStart', 'weekEnd'
        ));
    }

    // ─── Master Shift CRUD ────────────────────────────────────

    public function storeShift(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:100',
            'jam_masuk_start' => 'required|date_format:H:i',
            'jam_masuk_end'   => 'required|date_format:H:i',
            'batas_terlambat' => 'required|date_format:H:i',
            'jam_pulang'      => 'required|date_format:H:i',
        ]);

        Shift::create($request->only([
            'name', 'jam_masuk_start', 'jam_masuk_end', 'batas_terlambat', 'jam_pulang'
        ]));

        return redirect()->route('admin.shifts.index')
            ->with('success', 'Shift berhasil ditambahkan.');
    }

    public function updateShift(Request $request, Shift $shift)
    {
        $request->validate([
            'name'            => 'required|string|max:100',
            'jam_masuk_start' => 'required|date_format:H:i',
            'jam_masuk_end'   => 'required|date_format:H:i',
            'batas_terlambat' => 'required|date_format:H:i',
            'jam_pulang'      => 'required|date_format:H:i',
        ]);

        $shift->update($request->only([
            'name', 'jam_masuk_start', 'jam_masuk_end', 'batas_terlambat', 'jam_pulang'
        ]));

        return redirect()->route('admin.shifts.index')
            ->with('success', 'Shift berhasil diperbarui.');
    }

    public function destroyShift(Shift $shift)
    {
        $shift->delete();

        return redirect()->route('admin.shifts.index')
            ->with('success', 'Shift berhasil dihapus.');
    }

    // ─── Jadwal Shift Satpam ──────────────────────────────────

    public function storeSchedule(Request $request)
    {
        $request->validate([
            'user_id'  => 'required|exists:users,id',
            'shift_id' => 'required|exists:shifts,id',
            'tanggal'  => 'required|date',
        ]);

        ShiftSchedule::updateOrCreate(
            [
                'user_id' => $request->user_id,
                'tanggal' => $request->tanggal,
            ],
            [
                'shift_id' => $request->shift_id,
            ]
        );

        return redirect()->back()->with('success', 'Jadwal shift berhasil disimpan.');
    }

    /**
     * Simpan jadwal shift massal (bulk) untuk satu minggu.
     */
    public function storeBulkSchedule(Request $request)
    {
        $request->validate([
            'schedules'            => 'required|array',
            'schedules.*.user_id'  => 'required|exists:users,id',
            'schedules.*.tanggal'  => 'required|date',
            'schedules.*.shift_id' => 'nullable|exists:shifts,id',
        ]);

        foreach ($request->schedules as $schedule) {
            if (empty($schedule['shift_id'])) {
                // Hapus jadwal jika shift_id kosong (libur)
                ShiftSchedule::where('user_id', $schedule['user_id'])
                    ->where('tanggal', $schedule['tanggal'])
                    ->delete();
            } else {
                ShiftSchedule::updateOrCreate(
                    [
                        'user_id' => $schedule['user_id'],
                        'tanggal' => $schedule['tanggal'],
                    ],
                    [
                        'shift_id' => $schedule['shift_id'],
                    ]
                );
            }
        }

        return redirect()->back()->with('success', 'Jadwal shift mingguan berhasil disimpan.');
    }

    public function destroySchedule(ShiftSchedule $schedule)
    {
        $schedule->delete();

        return redirect()->back()->with('success', 'Jadwal shift berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $query = Permission::with(['user', 'approver']);

        if ($request->filled('status')) {
            $query->where('status_approval', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $permissions = $query->orderByRaw("FIELD(status_approval, 'pending', 'approved', 'rejected')")
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.permissions.index', compact('permissions'));
    }

    public function approve(Permission $permission)
    {
        $permission->update([
            'status_approval' => 'approved',
            'approved_by' => auth()->id(),
        ]);

        // Create attendance records for approved leave days
        $this->createLeaveAttendances($permission);

        return back()->with('success', 'Pengajuan berhasil disetujui.');
    }

    public function reject(Permission $permission)
    {
        $permission->update([
            'status_approval' => 'rejected',
            'approved_by' => auth()->id(),
        ]);

        return back()->with('success', 'Pengajuan berhasil ditolak.');
    }

    protected function createLeaveAttendances(Permission $permission)
    {
        $start = $permission->tanggal_mulai->copy();
        $end = $permission->tanggal_selesai->copy();

        while ($start->lte($end)) {
            if (! $start->isWeekend()) {
                Attendance::updateOrCreate(
                    [
                        'user_id' => $permission->user_id,
                        'tanggal' => $start->toDateString(),
                    ],
                    [
                        'status' => $permission->type,
                        'keterangan' => $permission->keterangan,
                    ]
                );
            }
            $start->addDay();
        }
    }
}

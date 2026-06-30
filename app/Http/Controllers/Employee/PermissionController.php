<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePermissionRequest;
use App\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('employee.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('employee.permissions.create');
    }

    public function store(StorePermissionRequest $request)
    {
        $data = [
            'user_id' => auth()->id(),
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'type' => $request->type,
            'keterangan' => $request->keterangan,
        ];

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('permission-attachments', 'public');
        }

        Permission::create($data);

        return redirect()->route('employee.permissions.index')
            ->with('success', 'Pengajuan izin/sakit berhasil dikirim.');
    }
}

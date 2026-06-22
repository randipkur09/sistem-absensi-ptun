<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOutsourcingRequest;
use App\Http\Requests\UpdateOutsourcingRequest;
use App\Models\User;
use App\Models\Role;
use App\Models\OutsourcingEmployee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OutsourcingController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('employee_type', 'outsourcing')
            ->with('outsourcingEmployee')
            ->whereHas('role', fn($q) => $q->where('name', 'pegawai'));

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('outsourcingEmployee', function ($q2) use ($search) {
                      $q2->where('company_name', 'like', "%{$search}%");
                  });
            });
        }

        $outsourcings = $query->orderBy('name')->paginate(15);

        return view('admin.outsourcing.index', compact('outsourcings'));
    }

    public function create()
    {
        return view('admin.outsourcing.create');
    }

    public function store(StoreOutsourcingRequest $request)
    {
        DB::beginTransaction();
        try {
            $pegawaiRole = Role::where('name', 'pegawai')->first();

            $userData = [
                'name'          => $request->name,
                'email'         => $request->email,
                'password'      => Hash::make($request->password),
                'role_id'       => $pegawaiRole->id,
                'employee_type' => 'outsourcing',
                'phone'         => $request->phone,
                'address'       => $request->address,
                'status'        => 'aktif',
            ];

            if ($request->hasFile('photo')) {
                $userData['photo'] = $request->file('photo')->store('user-photos', 'public');
            }

            $user = User::create($userData);

            OutsourcingEmployee::create([
                'user_id'         => $user->id,
                'company_name'    => $request->company_name,
                'position'        => $request->position,
                'contract_start'  => $request->contract_start,
                'contract_end'    => $request->contract_end,
                'contract_number' => $request->contract_number,
            ]);

            DB::commit();

            return redirect()->route('admin.outsourcing.index')
                ->with('success', 'Data tenaga outsourcing berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()]);
        }
    }

    public function show(User $outsourcing)
    {
        $outsourcing->load('outsourcingEmployee', 'attendances');
        return view('admin.outsourcing.show', compact('outsourcing'));
    }

    public function edit(User $outsourcing)
    {
        $outsourcing->load('outsourcingEmployee');
        return view('admin.outsourcing.edit', compact('outsourcing'));
    }

    public function update(UpdateOutsourcingRequest $request, User $outsourcing)
    {
        DB::beginTransaction();
        try {
            $userData = [
                'name'    => $request->name,
                'email'   => $request->email,
                'phone'   => $request->phone,
                'address' => $request->address,
                'status'  => $request->status,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            if ($request->hasFile('photo')) {
                if ($outsourcing->photo) {
                    Storage::disk('public')->delete($outsourcing->photo);
                }
                $userData['photo'] = $request->file('photo')->store('user-photos', 'public');
            }

            $outsourcing->update($userData);

            $outsourcing->outsourcingEmployee->update([
                'company_name'    => $request->company_name,
                'position'        => $request->position,
                'contract_start'  => $request->contract_start,
                'contract_end'    => $request->contract_end,
                'contract_number' => $request->contract_number,
            ]);

            DB::commit();

            return redirect()->route('admin.outsourcing.index')
                ->with('success', 'Data tenaga outsourcing berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Gagal memperbarui data: ' . $e->getMessage()]);
        }
    }

    public function destroy(User $outsourcing)
    {
        if ($outsourcing->photo) {
            Storage::disk('public')->delete($outsourcing->photo);
        }

        $outsourcing->delete();

        return redirect()->route('admin.outsourcing.index')
            ->with('success', 'Data tenaga outsourcing berhasil dihapus.');
    }
}

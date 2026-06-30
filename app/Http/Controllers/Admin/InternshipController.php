<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInternshipRequest;
use App\Http\Requests\UpdateInternshipRequest;
use App\Models\InternshipParticipant;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class InternshipController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('employee_type', 'magang')
            ->with('internshipParticipant')
            ->whereHas('role', fn ($q) => $q->where('name', 'pegawai'));

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('internshipParticipant', function ($q2) use ($search) {
                        $q2->where('institution', 'like', "%{$search}%");
                    });
            });
        }

        $internships = $query->orderBy('name')->paginate(15);

        return view('admin.internship.index', compact('internships'));
    }

    public function create()
    {
        return view('admin.internship.create');
    }

    public function store(StoreInternshipRequest $request)
    {
        DB::beginTransaction();
        try {
            $pegawaiRole = Role::where('name', 'pegawai')->first();

            $userData = [
                'name' => $request->name,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role_id' => $pegawaiRole->id,
                'employee_type' => 'magang',
                'phone' => $request->phone,
                'address' => $request->address,
                'status' => 'aktif',
            ];

            if ($request->hasFile('photo')) {
                $userData['photo'] = $request->file('photo')->store('user-photos', 'public');
            }

            $user = User::create($userData);

            InternshipParticipant::create([
                'user_id' => $user->id,
                'institution' => $request->institution,
                'major' => $request->major,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'supervisor' => $request->supervisor,
            ]);

            DB::commit();

            return redirect()->route('admin.internship.index')
                ->with('success', 'Data peserta magang berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->withErrors(['error' => 'Gagal menyimpan data: '.$e->getMessage()]);
        }
    }

    public function show(User $internship)
    {
        $internship->load('internshipParticipant', 'attendances');

        return view('admin.internship.show', compact('internship'));
    }

    public function edit(User $internship)
    {
        $internship->load('internshipParticipant');

        return view('admin.internship.edit', compact('internship'));
    }

    public function update(UpdateInternshipRequest $request, User $internship)
    {
        DB::beginTransaction();
        try {
            $userData = [
                'name' => $request->name,
                'username' => $request->username,
                'phone' => $request->phone,
                'address' => $request->address,
                'status' => $request->status,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            if ($request->hasFile('photo')) {
                if ($internship->photo) {
                    Storage::disk('public')->delete($internship->photo);
                }
                $userData['photo'] = $request->file('photo')->store('user-photos', 'public');
            }

            $internship->update($userData);

            $internship->internshipParticipant->update([
                'institution' => $request->institution,
                'major' => $request->major,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'supervisor' => $request->supervisor,
            ]);

            DB::commit();

            return redirect()->route('admin.internship.index')
                ->with('success', 'Data peserta magang berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->withErrors(['error' => 'Gagal memperbarui data: '.$e->getMessage()]);
        }
    }

    public function destroy(User $internship)
    {
        if ($internship->photo) {
            Storage::disk('public')->delete($internship->photo);
        }

        $internship->delete();

        return redirect()->route('admin.internship.index')
            ->with('success', 'Data peserta magang berhasil dihapus.');
    }
}

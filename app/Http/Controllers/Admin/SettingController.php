<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSettingRequest;
use App\Models\AttendanceSetting;

class SettingController extends Controller
{
    public function index()
    {
        $setting = AttendanceSetting::current();

        return view('admin.settings.index', compact('setting'));
    }

    public function update(UpdateSettingRequest $request)
    {
        $setting = AttendanceSetting::first();

        if (! $setting) {
            $setting = new AttendanceSetting;
        }

        $setting->fill($request->validated());
        $setting->save();

        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan berhasil diperbarui.');
    }
}

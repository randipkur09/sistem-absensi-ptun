<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'office_latitude'   => 'required|numeric|between:-90,90',
            'office_longitude'  => 'required|numeric|between:-180,180',
            'office_name'       => 'required|string|max:255',
            'office_address'    => 'nullable|string',
            'max_radius_meters' => 'required|integer|min:10|max:1000',
            'jam_masuk_start'   => 'required|date_format:H:i',
            'jam_masuk_end'     => 'required|date_format:H:i|after:jam_masuk_start',
            'jam_pulang'        => 'required|date_format:H:i|after:jam_masuk_end',
            'batas_terlambat'   => 'required|date_format:H:i',
        ];
    }

    public function messages(): array
    {
        return [
            'office_latitude.required'   => 'Latitude kantor wajib diisi.',
            'office_longitude.required'  => 'Longitude kantor wajib diisi.',
            'office_name.required'       => 'Nama kantor wajib diisi.',
            'max_radius_meters.required' => 'Radius maksimal wajib diisi.',
            'max_radius_meters.min'      => 'Radius minimal 10 meter.',
            'max_radius_meters.max'      => 'Radius maksimal 1000 meter.',
        ];
    }
}

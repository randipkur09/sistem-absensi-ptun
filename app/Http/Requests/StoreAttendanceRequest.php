<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'foto' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'latitude.required' => 'Lokasi GPS diperlukan.',
            'longitude.required' => 'Lokasi GPS diperlukan.',
            'foto.required' => 'Foto wajib diambil.',
        ];
    }
}

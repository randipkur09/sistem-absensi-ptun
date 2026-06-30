<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInternshipRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'username'       => 'required|string|unique:users,username',
            'password'    => 'required|string|min:6|confirmed',
            'phone'       => 'nullable|string|max:20',
            'address'     => 'nullable|string',
            'photo'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'institution' => 'required|string|max:255',
            'major'       => 'required|string|max:255',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after:start_date',
            'supervisor'  => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'        => 'Nama wajib diisi.',
            'email.required'       => 'Email wajib diisi.',
            'email.unique'         => 'Email sudah digunakan.',
            'password.required'    => 'Password wajib diisi.',
            'password.confirmed'   => 'Konfirmasi password tidak cocok.',
            'institution.required' => 'Nama institusi wajib diisi.',
            'major.required'       => 'Jurusan wajib diisi.',
            'start_date.required'  => 'Tanggal mulai wajib diisi.',
            'end_date.required'    => 'Tanggal selesai wajib diisi.',
            'end_date.after'       => 'Tanggal selesai harus setelah tanggal mulai.',
        ];
    }
}

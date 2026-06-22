<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOutsourcingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email',
            'password'        => 'required|string|min:6|confirmed',
            'phone'           => 'nullable|string|max:20',
            'address'         => 'nullable|string',
            'photo'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'company_name'    => 'required|string|max:255',
            'position'        => 'required|string|max:255',
            'contract_start'  => 'required|date',
            'contract_end'    => 'required|date|after:contract_start',
            'contract_number' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'           => 'Nama wajib diisi.',
            'email.required'          => 'Email wajib diisi.',
            'email.unique'            => 'Email sudah digunakan.',
            'password.required'       => 'Password wajib diisi.',
            'password.confirmed'      => 'Konfirmasi password tidak cocok.',
            'company_name.required'   => 'Nama perusahaan wajib diisi.',
            'position.required'       => 'Jabatan wajib diisi.',
            'contract_start.required' => 'Tanggal mulai kontrak wajib diisi.',
            'contract_end.required'   => 'Tanggal selesai kontrak wajib diisi.',
            'contract_end.after'      => 'Tanggal selesai harus setelah tanggal mulai.',
            'photo.image'             => 'File harus berupa gambar.',
            'photo.max'               => 'Ukuran foto maksimal 2MB.',
        ];
    }
}

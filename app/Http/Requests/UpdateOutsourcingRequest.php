<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOutsourcingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('outsourcing')->id ?? $this->route('outsourcing');
        return [
            'name'            => 'required|string|max:255',
            'username'        => 'required|string|unique:users,username,' . $userId,
            'password'        => 'nullable|string|min:6|confirmed',
            'phone'           => 'nullable|string|max:20',
            'address'         => 'nullable|string',
            'photo'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status'          => 'required|in:aktif,nonaktif',
            'company_name'    => 'required|string|max:255',
            'position'        => 'required|string|max:255',
            'contract_start'  => 'required|date',
            'contract_end'    => 'required|date|after:contract_start',
            'contract_number' => 'nullable|string|max:255',
        ];
    }
}

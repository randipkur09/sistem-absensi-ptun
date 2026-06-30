<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();

        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@ptun-bandarlampung.go.id',
            'password' => Hash::make('password123'),
            'role_id' => $adminRole->id,
            'status' => 'aktif',
        ]);
    }
}

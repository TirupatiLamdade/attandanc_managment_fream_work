<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class HIddenseeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Two',
            'email' => 'admin2@attendance.com',
            'password' => Hash::make('secret456'),
            'role' => 'admin',
        ]);
    }
}
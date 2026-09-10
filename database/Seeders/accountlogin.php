<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class accountlogin extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin One',
            'email' => 'admin1@attendance.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);
    }
}
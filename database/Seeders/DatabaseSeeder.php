<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@attendance.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Create Staff User
        User::create([
            'name' => 'Staff User',
            'email' => 'staff@attendance.com',
            'password' => Hash::make('staff123'),
            'role' => 'staff',
        ]);

        // Optional: Create demo folders
        $admin = User::where('email', 'admin@attendance.com')->first();
        
        \App\Models\Folder::create([
            'name' => 'Class A - FY BSc',
            'created_by' => $admin->id,
        ]);

        \App\Models\Folder::create([
            'name' => 'Class B - SY BSc',
            'created_by' => $admin->id,
        ]);

        // Optional: Create demo subject
        \App\Models\SubjectFolder::create([
            'name' => 'Mathematics',
            'created_by' => $admin->id,
        ]);
    }
}
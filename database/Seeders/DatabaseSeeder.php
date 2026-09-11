<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Folder;
use App\Models\SubjectFolder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Single Admin Account
        $admin = User::firstOrCreate(
            ['email' => 'admin@attendance.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // Staff User
        User::firstOrCreate(
            ['email' => 'staff@attendance.com'],
            [
                'name' => 'Staff User',
                'password' => Hash::make('staff123'),
                'role' => 'staff',
            ]
        );

        // Demo Folders
        Folder::firstOrCreate([
            'name' => 'Class A - FY BSc',
            'created_by' => $admin->id,
        ]);

        Folder::firstOrCreate([
            'name' => 'Class B - SY BSc',
            'created_by' => $admin->id,
        ]);

        SubjectFolder::firstOrCreate([
            'name' => 'Mathematics',
            'created_by' => $admin->id,
        ]);
    }
}
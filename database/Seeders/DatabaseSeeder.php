<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Call both admin seeder files
        $this->call([
            accountlogin::class,
            HIddenseeder::class,
        ]);

        // Create Staff User
        User::create([
            'name' => 'Staff User',
            'email' => 'staff@attendance.com',
            'password' => Hash::make('staff123'),
            'role' => 'staff',
        ]);

        // Demo folders mapping
        $admin = User::where('email', 'admin1@attendance.com')->first();
        
        \App\Models\Folder::create([
            'name' => 'Class A - FY BSc',
            'created_by' => $admin ? $admin->id : 1,
        ]);

        \App\Models\Folder::create([
            'name' => 'Class B - SY BSc',
            'created_by' => $admin ? $admin->id : 1,
        ]);

        \App\Models\SubjectFolder::create([
            'name' => 'Mathematics',
            'created_by' => $admin ? $admin->id : 1,
        ]);
    }
}
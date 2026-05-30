<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserAndRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin User
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'phone' => 'Super@123',
            'password' => Hash::make('Super@123'),
            'role' => 'admin',
            'addedBy' => 1, // Admin user ID
            'is_active' => 1, // Active
        ]);

        // Create Admin User
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'phone' => 'Admin@123',
            'password' => Hash::make('Admin@123'),
            'role' => 'admin',
            'addedBy' => 1, // Admin user ID
            'is_active' => 1, // Active
        ]);

        // Insert roles into roles table
        DB::table('roles')->upsert(
            [
                ['name' => 'Super Admin', 'guard_name' => 'web', 'defined_by' => 'system', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Admin', 'guard_name' => 'web', 'defined_by' => 'system', 'created_at' => now(), 'updated_at' => now()],
            ],
            ['name'], // Unique key(s) to determine if record exists
            ['guard_name', 'defined_by', 'updated_at'] // Columns to update if record exists
        );

        // Assign roles to users
        DB::table('model_has_roles')->insert([
            [
                'role_id' => 1,
                'model_type' => 'App\Models\User',
                'model_id' => 1,
            ],
            [
                'role_id' => 2,
                'model_type' => 'App\Models\User',
                'model_id' => 2,
            ],
        ]);
    }
}

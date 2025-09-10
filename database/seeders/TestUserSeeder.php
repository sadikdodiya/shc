<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class TestUserSeeder extends Seeder
{
    public function run()
    {
        // Create Super Admin role if it doesn't exist
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        
        // Create test admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'username' => 'admin',
                'phone' => '1234567890',
                'password' => Hash::make('password123'),
                'status' => 1, // 1 = active, 0 = inactive
                'email_verified_at' => now(),
            ]
        );

        // Assign Super Admin role
        if (!$admin->hasRole('Super Admin')) {
            $admin->assignRole($superAdminRole);
        }

        // Create test regular user
        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Test User',
                'username' => 'testuser',
                'phone' => '0987654321',
                'password' => Hash::make('password123'),
                'status' => 1, // 1 = active, 0 = inactive
                'email_verified_at' => now(),
            ]
        );

        // Assign user role if it exists
        $userRole = Role::firstOrCreate(['name' => 'user']);
        if (!$user->hasRole('user')) {
            $user->assignRole($userRole);
        }

        $this->command->info('Test users created successfully!');
        $this->command->info('Admin: admin@example.com / password123');
        $this->command->info('User: user@example.com / password123');
    }
}

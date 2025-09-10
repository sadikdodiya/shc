<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Create Super Admin role if it doesn't exist
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        
        // Create admin user or update if exists
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'username' => 'admin',
                'phone' => '1234567890',
                'password' => Hash::make('password123'),
                'status' => 1,
                'email_verified_at' => now(),
            ]
        );

        // Sync roles (remove all existing roles and add Super Admin)
        $admin->syncRoles(['Super Admin']);
        
        // Output information
        $this->command->info('Admin user created/updated with Super Admin role.');
        $this->command->info('Email: admin@example.com');
        $this->command->info('Password: password123');
    }
}

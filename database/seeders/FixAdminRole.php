<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class FixAdminRole extends Seeder
{
    public function run()
    {
        // Ensure Super Admin role exists
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        
        // Find or create admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'username' => 'admin',
                'phone' => '1234567890',
                'password' => bcrypt('password123'),
                'status' => 1,
                'email_verified_at' => now(),
            ]
        );

        // Remove all existing roles and assign Super Admin
        $admin->syncRoles(['Super Admin']);
        
        $this->command->info('Admin user has been assigned the Super Admin role.');
    }
}

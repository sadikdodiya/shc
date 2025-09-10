<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class FixAdminAccessSeeder extends Seeder
{
    public function run(): void
    {
        // Create or update the admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password123'),
                'status' => 1,
                'email_verified_at' => now(),
            ]
        );

        // Ensure Super Admin role exists
        $role = Role::firstOrCreate(['name' => 'Super Admin']);
        
        // Get all permissions
        $permissions = Permission::all();
        
        // Sync all permissions to the role
        $role->syncPermissions($permissions);
        
        // Sync role to admin user
        $admin->syncRoles([$role]);
        
        $this->command->info('Admin access fixed. You can now login with:');
        $this->command->info('Email: admin@example.com');
        $this->command->info('Password: password123');
    }
}

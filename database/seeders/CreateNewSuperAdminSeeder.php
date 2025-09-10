<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateNewSuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Create a new admin user
        $admin = User::create([
            'name' => 'System Admin',
            'email' => 'sysadmin@example.com',
            'password' => Hash::make('admin123'),
            'status' => 1,
            'email_verified_at' => now(),
        ]);

        // Create Super Admin role if it doesn't exist
        $role = Role::firstOrCreate(['name' => 'Super Admin']);
        
        // Get all permissions and assign them to the role
        $permissions = Permission::all();
        $role->syncPermissions($permissions);
        
        // Assign the role to the admin user
        $admin->assignRole($role);
        
        $this->command->info('New Super Admin created successfully!');
        $this->command->info('Email: sysadmin@example.com');
        $this->command->info('Password: admin123');
    }
}

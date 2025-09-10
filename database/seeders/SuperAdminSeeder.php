<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or get the super admin role
        $role = Role::firstOrCreate(['name' => 'Super Admin']);
        
        // Create super admin user
        $user = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'username' => 'superadmin',
                'name' => 'Super Admin',
                'email' => 'superadmin@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Assign super admin role to the user
        $user->assignRole($role);

        // Give all permissions to super admin
        $permissions = Permission::all();
        $role->syncPermissions($permissions);

        $this->command->info('Super Admin created successfully!');
        $this->command->info('Email: superadmin@example.com');
        $this->command->info('Password: password');
    }
}

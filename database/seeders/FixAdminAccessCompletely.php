<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class FixAdminAccessCompletely extends Seeder
{
    public function run(): void
    {
        // Clear existing role and permission caches
        app()['cache']->forget('spatie.permission.cache');
        
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');
        
        // Create permissions if they don't exist
        $permissions = [
            'view admin dashboard',
            'manage users',
            'manage roles',
            'manage permissions',
            'manage companies',
            'manage staff',
            'manage settings'
        ];
        
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
        
        // Create Super Admin role if it doesn't exist
        $role = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        
        // Assign all permissions to Super Admin role
        $role->syncPermissions(Permission::all());
        
        // Create or update admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Super Admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('admin123'),
                'status' => 1,
                'email_verified_at' => now(),
            ]
        );
        
        // Assign Super Admin role to the user
        $admin->syncRoles([$role]);
        
        // Clear the cache again to be safe
        app()['cache']->forget('spatie.permission.cache');
        
        $this->command->info('Admin access has been completely reset!');
        $this->command->info('Email: admin@admin.com');
        $this->command->info('Password: admin123');
    }
}

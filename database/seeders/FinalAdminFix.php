<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class FinalAdminFix extends Seeder
{
    public function run(): void
    {
        // Clear all caches
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Clear role and permission caches
        app()['cache']->forget('spatie.permission.cache');
        
        // Reset cached roles and permissions
        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('role_has_permissions')->truncate();
        DB::table('permissions')->truncate();
        DB::table('roles')->truncate();
        
        // Create permissions
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
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }
        
        // Create Super Admin role
        $role = Role::create(['name' => 'Super Admin', 'guard_name' => 'web']);
        
        // Assign all permissions to Super Admin role
        $role->syncPermissions(Permission::all());
        
        // Create admin user
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
        
        // Assign role to admin
        $admin->assignRole($role);
        
        // Verify the role assignment
        $admin->load('roles');
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $this->command->info('Admin access has been completely reset!');
        $this->command->info('Email: admin@admin.com');
        $this->command->info('Password: admin123');
        $this->command->info('Assigned roles: ' . $admin->getRoleNames());
    }
}

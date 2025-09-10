<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CompleteAdminFix extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Clear existing data
        $tables = [
            'model_has_roles',
            'model_has_permissions',
            'role_has_permissions',
            'permissions',
            'roles'
        ];
        
        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }
        
        // Create admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('admin123'),
                'status' => 1,
                'email_verified_at' => now(),
            ]
        );
        
        // Create Super Admin role
        $role = Role::create(['name' => 'Super Admin', 'guard_name' => 'web']);
        
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
        
        // Assign all permissions to role
        $role->syncPermissions(Permission::all());
        
        // Assign role to admin
        $admin->assignRole($role);
        
        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        // Clear cache
        app()['cache']->forget('spatie.permission.cache');
        
        $this->command->info('Admin access has been completely reset!');
        $this->command->info('Email: admin@admin.com');
        $this->command->info('Password: admin123');
        
        // Verify the setup
        $user = User::where('email', 'admin@admin.com')->first();
        $this->command->info('User has roles: ' . $user->getRoleNames());
        $this->command->info('User has permissions: ' . $user->getAllPermissions()->pluck('name'));
    }
}

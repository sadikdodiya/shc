<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AssignAdminRoleSeeder extends Seeder
{
    public function run(): void
    {
        // Find or create the Super Admin role
        $adminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        
        // Find the admin user by email
        $admin = User::where('email', 'admin@example.com')->first();
        
        if ($admin) {
            // Remove all existing roles and assign Super Admin
            $admin->syncRoles([$adminRole]);
            $this->command->info('Super Admin role has been assigned to admin@example.com');
        } else {
            $this->command->error('Admin user not found. Please run the AdminUserSeeder first.');
        }
    }
}

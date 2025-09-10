<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class CreateSuperAdminUserSeeder extends Seeder
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
        
        // Assign role to admin user
        $admin->syncRoles([$role]);
        
        $this->command->info('Super Admin user created/updated with email: admin@example.com and password: password123');
    }
}

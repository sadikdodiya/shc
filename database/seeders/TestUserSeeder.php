<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test user
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '+1234567890',
            'password' => Hash::make('password'),
            'status' => 'active',
            'email_verified_at' => null, // Ensure email is not verified
        ]);

        // Assign a role to the user (e.g., 'user' role)
        $role = Role::firstOrCreate(['name' => 'user']);
        $user->assignRole($role);

        $this->command->info('Test user created:');
        $this->command->info('Email: test@example.com');
        $this->command->info('Password: password');
    }
}

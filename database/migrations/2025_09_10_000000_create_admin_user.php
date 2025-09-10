<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up()
    {
        // Create admin user
        $userId = DB::table('users')->insertGetId([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'username' => 'admin',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Assign admin role if roles table exists
        if (\Schema::hasTable('roles')) {
            $adminRole = DB::table('roles')->where('name', 'admin')->first();
            if ($adminRole) {
                DB::table('model_has_roles')->insert([
                    'role_id' => $adminRole->id,
                    'model_type' => 'App\\Models\\User',
                    'model_id' => $userId
                ]);
            }
        }
    }

    public function down()
    {
        DB::table('users')->where('email', 'admin@example.com')->delete();
    }
};

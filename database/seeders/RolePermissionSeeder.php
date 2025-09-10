<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'user_management_access',
            'user_create',
            'user_edit',
            'user_show',
            'user_delete',
            'role_management_access',
            'role_create',
            'role_edit',
            'role_show',
            'role_delete',
            'permission_management_access',
            'service_management_access',
            'service_create',
            'service_edit',
            'service_delete',
            'customer_management_access',
            'customer_create',
            'customer_edit',
            'customer_delete',
            'appointment_management_access',
            'appointment_create',
            'appointment_edit',
            'appointment_delete',
            'report_view',
            'settings_manage',
        ];

        // Create permissions if they don't exist
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        // Create Company Admin role with all permissions
        $companyAdmin = Role::firstOrCreate(['name' => 'CompanyAdmin']);
        $companyAdmin->syncPermissions(Permission::all());

        // Create Super Admin role with all permissions
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $superAdmin->syncPermissions(Permission::all());

        // Create Admin role with specific permissions
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $adminPermissions = [
            'user_management_access',
            'user_create',
            'user_edit',
            'user_show',
            'service_management_access',
            'service_create',
            'service_edit',
            'customer_management_access',
            'customer_create',
            'customer_edit',
            'appointment_management_access',
            'appointment_create',
            'appointment_edit',
            'report_view',
        ];
        $admin->syncPermissions($adminPermissions);

        // Create Staff role with specific permissions
        $staff = Role::firstOrCreate(['name' => 'Staff']);
        $staffPermissions = [
            'service_management_access',
            'customer_management_access',
            'customer_create',
            'customer_edit',
            'appointment_management_access',
            'appointment_create',
            'appointment_edit',
        ];
        $staff->syncPermissions($staffPermissions);

        // Create Technician role with specific permissions
        $technician = Role::firstOrCreate(['name' => 'Technician']);
        $technicianPermissions = [
            'service_management_access',
            'appointment_management_access',
            'appointment_edit',
        ];
        $technician->syncPermissions($technicianPermissions);

        $customer = Role::create(['name' => 'Customer']);
        $customerPermissions = [
            'appointment_create',
            'appointment_edit',
        ];
        $customer->givePermissionTo($customerPermissions);
    }
}

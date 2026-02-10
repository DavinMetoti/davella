<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            'Dashboard',
            'manage users',
            'manage menus',
            'Cluster',
            'Units',
            'sales report',
            'Reservation',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create super_admin role and assign all permissions
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdminRole->givePermissionTo($permissions);

        // Create owner role
        $ownerRole = Role::firstOrCreate(['name' => 'Owner']);
        $ownerRole->givePermissionTo(['Dashboard', 'manage users', 'manage menus', 'Cluster', 'Units', 'sales report', 'Reservation']);

        // Create sales role
        $salesRole = Role::firstOrCreate(['name' => 'sales']);
        $salesRole->givePermissionTo(['Dashboard', 'Cluster', 'Units', 'sales report', 'Reservation']);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create super admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@davella.com',
            'password' => Hash::make('password'),
            'is_super_admin' => true,
            'is_active' => true,
        ]);
        $superAdmin->assignRole('super_admin');

        // Create owner
        $owner = User::create([
            'name' => 'Owner User',
            'email' => 'owner@davella.com',
            'password' => Hash::make('password'),
            'role' => 'Owner',
            'is_active' => true,
        ]);
        $owner->assignRole('Owner');

        // Create sales
        $sales = User::create([
            'name' => 'Sales User',
            'email' => 'sales@davella.com',
            'password' => Hash::make('password'),
            'role' => 'sales',
            'is_active' => true,
        ]);
        $sales->assignRole('sales');
    }
}

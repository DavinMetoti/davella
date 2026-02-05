<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Company;
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

        // Get all companies
        $companies = Company::all();

        foreach ($companies as $company) {
            // Create owner
            $owner = User::create([
                'name' => 'Owner ' . $company->name,
                'email' => 'owner' . $company->id . '@davella.com',
                'password' => Hash::make('password'),
                'company_id' => $company->id,
                'role' => 'owner',
                'is_active' => true,
            ]);
            $owner->assignRole('owner');

            // Create 2 admins
            for ($i = 1; $i <= 2; $i++) {
                $admin = User::create([
                    'name' => 'Admin ' . $i . ' ' . $company->name,
                    'email' => 'admin' . $i . $company->id . '@davella.com',
                    'password' => Hash::make('password'),
                    'company_id' => $company->id,
                    'role' => 'admin',
                    'is_active' => true,
                ]);
                $admin->assignRole('admin');
            }

            // Create 2 members
            for ($i = 1; $i <= 2; $i++) {
                $member = User::create([
                    'name' => 'Member ' . $i . ' ' . $company->name,
                    'email' => 'member' . $i . $company->id . '@davella.com',
                    'password' => Hash::make('password'),
                    'company_id' => $company->id,
                    'role' => 'member',
                    'is_active' => true,
                ]);
                $member->assignRole('member');
            }
        }
    }
}

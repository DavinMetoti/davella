<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = [
            [
                'name' => 'PT. Perumahan Maju',
                'slug' => 'pt-perumahan-maju',
            ],
            [
                'name' => 'CV. Rumah Idaman',
                'slug' => 'cv-rumah-idaman',
            ],
            [
                'name' => 'PT. Properti Sejahtera',
                'slug' => 'pt-properti-sejahtera',
            ],
        ];

        foreach ($companies as $company) {
            Company::create($company);
        }
    }
}

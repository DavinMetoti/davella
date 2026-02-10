<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample cluster if none exists
        $cluster = \App\Models\Cluster::first();
        if (!$cluster) {
            $cluster = \App\Models\Cluster::create([
                'name' => 'Sample Cluster',
                'description' => 'Sample cluster for reservations',
                'address' => 'Jl. Sample No. 123',
                'latitude' => -6.2088,
                'longitude' => 106.8456,
                'area_size' => '5',
                'total_units' => 50,
                'available_units' => 45,
                'price_range_min' => 50000000,
                'price_range_max' => 200000000,
                'year_built' => 2024,
                'facilities' => ['pool', 'gym', 'garden'],
                'is_active' => true,
            ]);
        }

        // Create sample units if none exist
        if (\App\Models\Unit::count() === 0) {
            for ($i = 1; $i <= 20; $i++) {
                \App\Models\Unit::create([
                    'cluster_id' => $cluster->id,
                    'name' => 'Unit ' . $i,
                    'block' => 'A',
                    'number' => str_pad($i, 3, '0', STR_PAD_LEFT),
                    'house_type' => ['Type 36', 'Type 45', 'Type 60'][array_rand(['Type 36', 'Type 45', 'Type 60'])],
                    'land_area' => rand(60, 120),
                    'building_area' => rand(36, 60),
                    'progress' => rand(0, 100),
                    'status' => ['available', 'reserved'][array_rand(['available', 'reserved'])],
                    'price' => rand(50000000, 200000000),
                ]);
            }
        }

        $units = \App\Models\Unit::all();
        $salesUsers = \App\Models\User::role('sales')->get();

        if ($units->isEmpty() || $salesUsers->isEmpty()) {
            return; // Skip if no units or sales users exist
        }

        $statuses = ['pending', 'confirmed', 'cancelled', 'expired'];
        $paymentMethods = ['cash', 'transfer', 'credit_card'];

        // Create 10 sample reservations
        for ($i = 1; $i <= 10; $i++) {
            $unit = $units->random();
            $sales = $salesUsers->random();
            $reservationDate = now()->subDays(rand(0, 30));
            $status = $statuses[array_rand($statuses)];

            \App\Models\Reservation::create([
                'reservation_code' => 'RSV' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'reservation_date' => $reservationDate,
                'expired_at' => $reservationDate->copy()->addDays(7), // 7 days expiry
                'unit_id' => $unit->id,
                'price_snapshot' => $unit->price ?? rand(50000000, 200000000),
                'promo_snapshot' => rand(0, 1) ? ['discount' => rand(5, 15) . '%'] : null,
                'customer_name' => 'Customer ' . $i,
                'customer_phone' => '081' . rand(10000000, 99999999),
                'ktp_number' => rand(1000000000000000, 9999999999999999),
                'sales_id' => $sales->id,
                'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                'booking_fee' => rand(1000000, 5000000),
                'dp_plan' => rand(10000000, 50000000),
                'status' => $status,
                'created_by' => $sales->id,
            ]);
        }
    }
}

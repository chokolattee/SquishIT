<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShippingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('shippings')->insert([
            [
                'region' => 'Within Metro Manila',
                'rate' => 50.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'region' => 'Luzon (Outside Metro Manila)',
                'rate' => 70.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'region' => 'Visayas',
                'rate' => 90.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'region' => 'Mindanao',
                'rate' => 110.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'region' => 'Overseas',
                'rate' => 200.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]
    );
    }
}
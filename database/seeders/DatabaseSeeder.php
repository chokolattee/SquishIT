<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\ItemSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // $this->call(UserSeeder::class);
        // $this->call(CategorySeeder::class);
        // $this->call(StatusSeeder::class);
        // $this->call(ItemSeeder::class);
        // $this->call(ShippingSeeder::class);
        // $this->call(OrderSeeder::class);
        // $this->call(ReviewSeeder::class);
        $this->call(BadWordsSeeder::class);
    }
    }


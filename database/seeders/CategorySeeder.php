<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('category')->insert([
            ['category_id' => 1, 'description' => 'Squishmallows'],
            ['category_id' => 2, 'description' => 'Keychain Plushies'],
            ['category_id' => 3, 'description' => 'Mini Plushies'],
            ['category_id' => 4, 'description' => 'Giant Plushies'],
            ['category_id' => 5, 'description' => 'Pillow Plushies'],
        ]);
    }
}

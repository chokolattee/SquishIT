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
        DB::table('categories')->insert([
            ['id' => 1, 'description' => 'Squishmallows'],
            ['id' => 2, 'description' => 'Keychain Plushies'],
            ['id' => 3, 'description' => 'Mini Plushies'],
            ['id' => 4, 'description' => 'Giant Plushies'],
            ['id' => 5, 'description' => 'Pillow Plushies'],
        ]);
    }
}

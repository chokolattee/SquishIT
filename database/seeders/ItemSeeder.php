<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as FakerFactory;
use App\Models\Item;
use App\Models\ItemImage;
use App\Models\Stock;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = FakerFactory::create();

        $categories = [
            1 => 'Squishmallows',
            2 => 'Keychain Plushie',
            3 => 'Mini Plushies',
            4 => 'Giant Plushies',
            5 => 'Pillow Plushies'
        ];

        $itemNames = [
            'Squishmallows' => ['Penguin', 'Octopus', 'Dragon', 'Unicorn', 'Cat'],
            'Keychain Plushie' => ['Bear', 'Rabbit', 'Fox', 'Dog', 'Sloth'],
            'Mini Plushies' => ['Dinosaur', 'Elephant', 'Panda', 'Koala', 'Monkey'],
            'Giant Plushies' => ['Teddy Bear', 'Narwhal', 'Shark', 'Llama', 'Hippo'],
            'Pillow Plushies' => ['Avocado', 'Pizza', 'Burger', 'Sushi', 'Rainbow']
        ];

        for ($i = 0; $i < 30; $i++) {
            $categoryId = $faker->numberBetween(1, 5);
            $categoryName = $categories[$categoryId];
            $itemName = $faker->randomElement($itemNames[$categoryName]);

            // Create the item
            $item = Item::create([
                'item_name' => $itemName . ' ' . $categoryName,
                'description' => $faker->realTextBetween(50, 100),
                'cost_price' => $faker->randomFloat(2, 5, 20),
                'sell_price' => $faker->randomFloat(2, 10, 40),
                'category_id' => $categoryId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create item images
            $imageCount = $faker->numberBetween(2, 4);
            for ($j = 0; $j < $imageCount; $j++) {
                ItemImage::create([
                    'item_id' => $item->id,
                    'image_path' => $faker->imageUrl(640, 480, $itemName, true, 'Plushie'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Create stock
            Stock::create([
                'item_id' => $item->id,
                'quantity' => $faker->numberBetween(5, 100),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        
        // Get all order items that could have reviews
        $orderItems = DB::table('order_item as oi')
                        ->join('orders as o', 'oi.order_id', '=', 'o.id')
                        ->join('statuses as s', 'o.status_id', '=', 's.id')
                        ->where('s.status', 'Delivered')
                        ->select('oi.*', 'o.customer_id')
                        ->get();
        
        foreach ($orderItems as $orderItem) {
            if ($faker->boolean(30)) {
                $now = Carbon::now();
                $reviewCreationDate = $faker->dateTimeBetween('-6 months', 'now');
                
                // Create review
                $reviewId = DB::table('reviews')->insertGetId([
                    'order_id' => $orderItem->order_id,
                    'customer_id' => $orderItem->customer_id,
                    'item_id' => $orderItem->item_id,
                    'rating' => $faker->numberBetween(1, 5),
                    'review_text' => $faker->realText($faker->numberBetween(50, 200)),
                    'created_at' => $reviewCreationDate,
                    'updated_at' => $reviewCreationDate,
                    'deleted_at' => null,
                ]);
                
                // Create between 0 and 3 review images
                $imageCount = $faker->numberBetween(0, 3);
                
                for ($i = 0; $i < $imageCount; $i++) {
                    DB::table('review_images')->insert([
                        'review_id' => $reviewId,
                        'image_path' => 'reviews/' . $faker->uuid . '.' . $faker->randomElement(['jpg', 'png', 'jpeg']),
                        'created_at' => $reviewCreationDate,
                        'updated_at' => $reviewCreationDate,
                        'deleted_at' => null,
                    ]);
                }
            }
        }
    }
}
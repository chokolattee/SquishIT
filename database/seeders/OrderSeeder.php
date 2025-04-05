<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Item;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds to create sample orders with items.
     */
    public function run(): void
    {
        $customers = Customer::all();
        $items = Item::all();

        $shippingRegions = DB::table('shippings')->get();
        
        if ($customers->isEmpty()) {
            $this->command->info('No customers found. Please run CustomerSeeder first.');
            return;
        }
        
        if ($items->isEmpty()) {
            $this->command->info('No items found. Please run ItemSeeder first.');
            return;
        }
        
        for ($i = 0; $i < 20; $i++) {
            $customer = $customers->random();
            
            $datePlaced = Carbon::now()->subDays(rand(1, 365));
            
            $status = $this->getRandomStatus();
            
            $dateShipped = null;
            $dateDelivered = null;
            
            if ($status == 'Shipped' || $status == 'Delivered') {
                // If shipped or delivered, add shipping date (1-3 days after placed)
                $dateShipped = (clone $datePlaced)->addDays(rand(1, 3));
            }
            
            if ($status == 'Delivered' && $dateShipped) {
                // If delivered, add delivery date (1-3 days after shipped)
                $dateDelivered = (clone $dateShipped)->addDays(rand(1, 3));
            }
            
            $statusId = $this->getStatusId($status);
            
            $selectedShipping = $shippingRegions->random();
            $shippingId = $selectedShipping->id;

            $order = Order::create([
                'customer_id' => $customer->id,
                'date_placed' => $datePlaced,
                'date_shipped' => $dateShipped,
                'date_delivered' => $dateDelivered,
                'shipping_id' => $shippingId,
                'status_id' => $statusId,
                'created_at' => $datePlaced,
                'updated_at' => $datePlaced,
            ]);
            
            $orderTotal = 0;
            $itemCount = rand(1, 5);
            $orderItems = $items->random($itemCount);
            
            foreach ($orderItems as $item) {
                $stockRecord = DB::table('item_stock')->where('item_id', $item->id)->first();
                
                if (!$stockRecord) {
                    continue;
                }
                
                $currentStock = $stockRecord->quantity ?? 0;
                $quantity = rand(1, 3);
                
                if ($currentStock > 0 && $currentStock < $quantity) {
                    $quantity = $currentStock;
                }
                
                if ($currentStock <= 0) {
                    continue;
                }
                
                DB::table('item_stock')
                    ->where('item_id', $item->id)
                    ->update([
                        'quantity' => $currentStock - $quantity,
                        'updated_at' => now()
                    ]);
                
                $price = $item->sell_price;
                $itemTotal = $price * $quantity;
                $orderTotal += $itemTotal;
                
                DB::table('order_item')->insert([
                    'order_id' => $order->id,
                    'item_id' => $item->id,
                    'quantity' => $quantity,
                    'created_at' => $datePlaced,
                    'updated_at' => $datePlaced,
                ]);
            }
        }
    }
    
    /**
     * Get a random order status
     * 
     * @return string
     */
    private function getRandomStatus(): string
    {
        $weightedStatuses = array_merge(
            array_fill(0, 1, 'Pending'),
            array_fill(0, 1, 'Shipped'),
            array_fill(0, 4, 'Delivered'),
            array_fill(0, 1, 'Cancelled')
        );
        
        return $weightedStatuses[array_rand($weightedStatuses)];
    }
    
    /**
     * Map status string to status ID
     * 
     * @param string $status
     * @return int
     */
    private function getStatusId(string $status): int
    {
        $statusMap = [
            'Pending' => 1,
            'Shipped' => 2,
            'Delivered' => 3,
            'Cancelled' => 4
        ];
        
        return $statusMap[$status] ?? 1; 
    }
}
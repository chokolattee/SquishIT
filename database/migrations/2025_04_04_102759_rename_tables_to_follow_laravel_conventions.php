<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('follow_laravel_conventions', function (Blueprint $table) {
            Schema::rename('category', 'categories');
        Schema::rename('customer', 'customers');
        Schema::rename('item', 'items');
        Schema::rename('orderinfo', 'orders');
        Schema::rename('orderline', 'order_item');
        Schema::rename('shipping', 'shippings');
        Schema::rename('stock', 'item_stock');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('follow_laravel_conventions', function (Blueprint $table) {
            Schema::rename('categories', 'category');
        Schema::rename('customers', 'customer');
        Schema::rename('items', 'item');
        Schema::rename('orders', 'orderinfo');
        Schema::rename('order_item', 'orderline');
        Schema::rename('shippings', 'shipping');
        Schema::rename('item_stocks', 'stock');
        });
    }
};

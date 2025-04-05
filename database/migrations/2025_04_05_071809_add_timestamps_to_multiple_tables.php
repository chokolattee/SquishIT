<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = ['categories', 'customers', 'item_stock', 'orders', 'order_item', 'order_statuses', 'shippings'];
        
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $blueprint) use ($table) {
                    if (!Schema::hasColumn($table, 'created_at')) {
                        $blueprint->timestamp('created_at')->nullable();
                    }
                    if (!Schema::hasColumn($table, 'updated_at')) {
                        $blueprint->timestamp('updated_at')->nullable();
                    }
                });

                // Update existing records with current timestamps
                if (Schema::hasColumn($table, 'created_at')) {
                    DB::table($table)->whereNull('created_at')->update(['created_at' => now()]);
                }
                if (Schema::hasColumn($table, 'updated_at')) {
                    DB::table($table)->whereNull('updated_at')->update(['updated_at' => now()]);
                }
            }
        }
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['categories', 'customers', 'item_stock', 'orders', 'order_item', 'statuses'];
        
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $table) {
                    $table->dropColumn(['created_at', 'updated_at']);
                });
            }
        }
    }
};

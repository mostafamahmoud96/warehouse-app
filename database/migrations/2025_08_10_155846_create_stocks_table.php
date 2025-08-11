<?php

use App\Models\InventoryItem;
use App\Models\Warehouse;
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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Warehouse::class);
            $table->foreignIdFor(InventoryItem::class); // product reference
            $table->integer('quantity')->default(0);
            $table->boolean('is_alerted')->default(false);
            $table->index(['warehouse_id', 'inventory_item_id']);
            $table->unique(['warehouse_id', 'inventory_item_id'], 'unique_warehouse_inventory');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};

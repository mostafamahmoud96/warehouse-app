<?php

use App\Models\Warehouse;
use App\Models\InventoryItem;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Warehouse::class, 'from_warehouse_id');
            $table->foreignIdFor(Warehouse::class, 'to_warehouse_id');
            $table->foreignIdFor(InventoryItem::class);
            $table->integer('quantity')->default(0);
            $table->timestamp('transfer_date');

            $table->index(['from_warehouse_id', 'to_warehouse_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transfers');
    }
};

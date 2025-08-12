<?php
namespace Database\Seeders;

use App\Models\InventoryItem;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StockTransferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warehouses = Warehouse::all();

        foreach ($warehouses as $fromWarehouse) {
            foreach ($warehouses as $toWarehouse) {
                if ($fromWarehouse->id !== $toWarehouse->id) {
                    $inventoryItem = InventoryItem::inRandomOrder()->first();
                    $quantity      = rand(1, 10);

                    $currentStock = $fromWarehouse->stocks()
                        ->where('inventory_item_id', $inventoryItem->id)
                        ->value('quantity');

                    if ($currentStock >= $quantity) {
                        $fromWarehouse->outgoingStockTransfers()->create([
                            'to_warehouse_id'   => $toWarehouse->id,
                            'inventory_item_id' => $inventoryItem->id,
                            'quantity'          => $quantity,
                            'transfer_date'     => now(),
                        ]);

                        $toWarehouse->stocks()
                            ->updateExistingPivot($inventoryItem->id, [
                                'quantity' => DB::raw('quantity + ' . $quantity),
                            ]);

                        $fromWarehouse->stocks()
                            ->updateExistingPivot($inventoryItem->id, [
                                'quantity' => DB::raw('quantity - ' . $quantity),
                            ]);

                    }
                }
            }
        }
    }
}

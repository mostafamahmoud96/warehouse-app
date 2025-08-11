<?php
namespace Database\Seeders;

use App\Models\InventoryItem;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class InventoryItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 20 inventory items
        InventoryItem::factory(20)->create();

        // Assign random stock quantities to each warehouse for each inventory item
        Warehouse::all()->each(function ($warehouse) {
            $inventoryItems = InventoryItem::all();

            $stocks = $inventoryItems->mapWithKeys(function ($inventoryItem) {
                return [
                    $inventoryItem->id => [
                        'quantity'   => rand(1, 100),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                ];
            });

            $warehouse->stocks()->sync($stocks);
        });
    }
}

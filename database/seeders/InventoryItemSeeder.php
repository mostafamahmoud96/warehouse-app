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
        InventoryItem::factory(20)->create();

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

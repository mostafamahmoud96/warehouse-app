<?php

use App\Events\LowStockDetected;
use App\Exceptions\CantDecreaseStockBelowZero;
use App\Exceptions\InsufficientQuantity;
use App\Models\InventoryItem;
use App\Models\Warehouse;
use App\Services\StockService;
use App\Services\StockTransferService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

uses(RefreshDatabase::class);

it('prevents stock update when over-updating', function () {

    $warehouse     = Warehouse::factory()->create();
    $inventoryItem = InventoryItem::factory()->create();

    $warehouse->stocks()->attach($inventoryItem->id, ['quantity' => 10]);

    $stockService = app(StockService::class);

    $stockService->AddEditStock([
        'warehouseId' => $warehouse->id,
        'items'       => [
            [
                'inventoryItemId' => $inventoryItem->id,
                'quantity'        => 15,
                'transactionType' => 'decrease',
            ],
        ],
    ], $warehouse->id);

})->throws(CantDecreaseStockBelowZero::class, 'Cannot decrease stock below zero');

it('prevents stock transfer when over quantity provided', function () {

    $fromWarehouse = Warehouse::factory()->create();
    $toWarehouse   = Warehouse::factory()->create();
    $inventoryItem = InventoryItem::factory()->create([
        'name' => 'Test Item',
    ]);

    $fromWarehouse->stocks()->attach($inventoryItem->id, ['quantity' => 10]);

    $stockTransferService = app(StockTransferService::class);

    $this->expectException(InsufficientQuantity::class);

    $stockTransferService->stockTransfer([
        'fromWarehouseId' => $fromWarehouse->id,
        'toWarehouseId'   => $toWarehouse->id,
        'items'           => [
            [
                'inventoryItemId' => $inventoryItem->id,
                'quantity'        => 15,
            ],
        ],
    ]);
});

it('fires LowStockDetected event when stock falls below threshold', function () {
    Event::fake();

    $fromWarehouse = Warehouse::factory()->create();
    $toWarehouse   = Warehouse::factory()->create();
    $inventoryItem = InventoryItem::factory()->create();

    $fromWarehouse->stocks()->attach($inventoryItem->id, ['quantity' => 10]);

    $stockTransferService = app(StockTransferService::class);
    $stockTransferService->stockTransfer([
        'fromWarehouseId' => $fromWarehouse->id,
        'toWarehouseId'   => $toWarehouse->id,
        'items'           => [
            [
                'inventoryItemId' => $inventoryItem->id,
                'quantity'        => 9,
            ],
        ],
    ]);

    Event::assertDispatched(LowStockDetected::class, function ($event) use ($inventoryItem) {
        return $event->alertedQuantities->contains(function ($alert) use ($inventoryItem) {
            return $alert->pivot->inventory_item_id == $inventoryItem->id;
        });
    });
});

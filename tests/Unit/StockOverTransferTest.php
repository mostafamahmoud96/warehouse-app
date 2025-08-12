<?php

use App\Events\LowStockDetected;
use App\Exceptions\CantDecreaseStockBelowZero;
use App\Exceptions\InsufficientQuantity;
use App\Exceptions\UnauthorizedActionException;
use App\Http\Controllers\Warehouse\StockTransferController;
use App\Http\Requests\CreateStockTransfeRequest;
use App\Models\InventoryItem;
use App\Models\Stock;
use App\Models\User;
use App\Models\Warehouse;
use App\Repositories\WarehouseRepository;
use App\Services\StockService;
use App\Services\StockTransferService;
use App\Util\PermissionsUtil;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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

it('allows stock transfer when sufficient quantity is available', function () {

    $fromWarehouse = Warehouse::factory()->create();
    $toWarehouse   = Warehouse::factory()->create();
    $inventoryItem = InventoryItem::factory()->create();

    $fromWarehouse->stocks()->attach($inventoryItem->id, ['quantity' => 10]);

    $stockTransferService = app(StockTransferService::class);

    $transfers = $stockTransferService->stockTransfer([
        'fromWarehouseId' => $fromWarehouse->id,
        'toWarehouseId'   => $toWarehouse->id,
        'items'           => [
            [
                'inventoryItemId' => $inventoryItem->id,
                'quantity'        => 5,
            ],
        ],
    ]);

    expect($transfers)->toHaveCount(1);

    expect($transfers[0]['from_warehouse_id'])->toBe($fromWarehouse->id);
    expect($transfers[0]['to_warehouse_id'])->toBe($toWarehouse->id);
    expect($transfers[0]['inventory_item_id'])->toBe($inventoryItem->id);
    expect($transfers[0]['quantity'])->toBe(5);
    expect($transfers[0]['transfer_date'])->toBeString();
});

it('allows stock transfer with multiple items', function () {

    $fromWarehouse  = Warehouse::factory()->create();
    $toWarehouse    = Warehouse::factory()->create();
    $inventoryItem1 = InventoryItem::factory()->create();
    $inventoryItem2 = InventoryItem::factory()->create();

    $fromWarehouse->stocks()->attach($inventoryItem1->id, ['quantity' => 10]);
    $fromWarehouse->stocks()->attach($inventoryItem2->id, ['quantity' => 20]);

    $stockTransferService = app(StockTransferService::class);

    $transfers = $stockTransferService->stockTransfer([
        'fromWarehouseId' => $fromWarehouse->id,
        'toWarehouseId'   => $toWarehouse->id,
        'items'           => [
            [
                'inventoryItemId' => $inventoryItem1->id,
                'quantity'        => 5,
            ],
            [
                'inventoryItemId' => $inventoryItem2->id,
                'quantity'        => 10,
            ],
        ],
    ]);

    expect($transfers)->toHaveCount(2);

    expect($transfers[0]['from_warehouse_id'])->toBe($fromWarehouse->id);
    expect($transfers[0]['to_warehouse_id'])->toBe($toWarehouse->id);
    expect($transfers[0]['inventory_item_id'])->toBe($inventoryItem1->id);
    expect($transfers[0]['quantity'])->toBe(5);

    expect($transfers[1]['from_warehouse_id'])->toBe($fromWarehouse->id);
    expect($transfers[1]['to_warehouse_id'])->toBe($toWarehouse->id);
    expect($transfers[1]['inventory_item_id'])->toBe($inventoryItem2->id);
    expect($transfers[1]['quantity'])->toBe(10);
});

it('does not allow stock transfer when user has permission', function () {

    $user       = User::factory()->create();
    $role       = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'api']);
    $newRole    = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'api']);
    $permission = Permission::firstOrCreate(['name' => PermissionsUtil::UPDATE_STOCK_TRANSFER, 'guard_name' => 'api']);
    $newRole->givePermissionTo($permission);
    $user->assignRole($role);
    $this->actingAs($user);

    $fromWarehouse = Warehouse::factory()->create();
    $toWarehouse   = Warehouse::factory()->create();
    $inventoryItem = InventoryItem::factory()->create();

    $fromWarehouse->stocks()->attach($inventoryItem->id, ['quantity' => 10]);

    $requestData = [
        'fromWarehouseId' => $fromWarehouse->id,
        'toWarehouseId'   => $toWarehouse->id,
        'items'           => [
            [
                'inventoryItemId' => $inventoryItem->id,
                'quantity'        => 5,
            ],
        ],
    ];

    $this->expectException(UnauthorizedActionException::class);
    app(StockTransferController::class)->stockTransfer(new CreateStockTransfeRequest($requestData));

});

it('allows stock transfer when user has permission', function () {

    $user       = User::factory()->create();
    $role       = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'api']);
    $permission = Permission::firstOrCreate(['name' => PermissionsUtil::UPDATE_STOCK_TRANSFER, 'guard_name' => 'api']);
    $role->givePermissionTo($permission);
    $user->assignRole($role);
    $this->actingAs($user);

    $fromWarehouse = Warehouse::factory()->create();
    $toWarehouse   = Warehouse::factory()->create();
    $inventoryItem = InventoryItem::factory()->create();

    $fromWarehouse->stocks()->attach($inventoryItem->id, ['quantity' => 10]);

    $requestData = [
        'fromWarehouseId' => $fromWarehouse->id,
        'toWarehouseId'   => $toWarehouse->id,
        'items'           => [
            [
                'inventoryItemId' => $inventoryItem->id,
                'quantity'        => 5,
            ],
        ],
    ];

    $response = $this->postJson(route('stock.transfer'), $requestData); // call route

    $response->assertStatus(200);

});

it('validate stock transfer request', function () {

    $user       = User::factory()->create();
    $role       = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'api']);
    $permission = Permission::firstOrCreate(['name' => PermissionsUtil::UPDATE_STOCK_TRANSFER, 'guard_name' => 'api']);
    $role->givePermissionTo($permission);
    $user->assignRole($role);
    $this->actingAs($user);

    $fromWarehouse = Warehouse::factory()->create();
    $toWarehouse   = Warehouse::factory()->create();
    $inventoryItem = InventoryItem::factory()->create();

    $fromWarehouse->stocks()->attach($inventoryItem->id, ['quantity' => 10]);

    $requestData = [
        'fromWarehouseId' => $fromWarehouse->id,
        'toWarehouseId'   => $toWarehouse->id,
        'items'           => [
            [
                'inventoryItemId' => $inventoryItem->id,
                'quantity'        => 5,
            ],
        ],
    ];

    $response = $this->postJson(route('stock.transfer'), $requestData);
    $response->assertStatus(200);

    unset($requestData['fromWarehouseId']);
    $response = $this->postJson(route('stock.transfer'), $requestData);
    $response->assertStatus(422);
});

it('retrieves paginated inventory for a specific warehouse', function () {
    $warehouse = Warehouse::factory()->create();
    $stocks    = Stock::factory()->count(15)->create(['warehouse_id' => $warehouse->id]);

    $repository = new WarehouseRepository(new Warehouse());

    $page    = 1;
    $perPage = 10;

    $result = $repository->getInventory($page, $perPage, $warehouse->id);

    expect($result)->toBeInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class);
    expect($result->perPage())->toBe($perPage);
    expect($result->currentPage())->toBe($page);
});

it('caches the inventory result for subsequent calls', function () {
    $warehouse = Warehouse::factory()->create();
    Stock::factory()->count(5)->create(['warehouse_id' => $warehouse->id]);

    $repository = new WarehouseRepository(new Warehouse());

    $page    = 1;
    $perPage = 5;

    Cache::shouldReceive('remember')
        ->once()
        ->with("warehouse_inventory_{$warehouse->id}_page_{$page}_perPage_{$perPage}", 3600, \Closure::class)
        ->andReturnUsing(function ($key, $ttl, $callback) {
            return $callback();
        });

    $result = $repository->getInventory($page, $perPage, $warehouse->id);

    expect($result)->toBeInstanceOf(LengthAwarePaginator::class);
});

it('filters inventory items based on provided filters', function () {

    $warehouse = Warehouse::factory()->create();

    $item1 = InventoryItem::factory()->create(['name' => 'Item A']);
    $item2 = InventoryItem::factory()->create(['name' => 'second B']);
    $item3 = InventoryItem::factory()->create(['name' => 'third C']);

    $warehouse->stocks()->attach($item1->id, ['quantity' => 10]);
    $warehouse->stocks()->attach($item2->id, ['quantity' => 5]);
    $warehouse->stocks()->attach($item3->id, ['quantity' => 20]);

    $repository = new WarehouseRepository(new Warehouse());

    $filters = [
        'name' => 'Item',
    ];

    $result = $repository->getInventory(1, 10, $warehouse->id, $filters);

    expect($result)->toBeInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class);
    expect($result->total())->toBe(1);
});

<?php
namespace App\Repositories;

use App\Models\InventoryItem;

class InventoryItemRepository
{
    /**
     * Create a new repository instance.
     * @param InventoryItem $model
     */
    public function __construct(public InventoryItem $model)
    {}

    /**
     * Get paginated inventory items with optional filters.
     *
     * @param int $page
     * @param int $perPage
     * @param Filter $filter
     * @return mixed
     */
    public function index(int $page, int $perPage, $filter)
    {
        $columns = [
            'inventory_items.id',
            'inventory_items.name',
            'inventory_items.created_at',
            'inventory_items.price',
        ];

        $items = InventoryItem::query()
            ->with(['stocks'])
            ->filter($filter)
            ->paginate($perPage, $columns, 'page', $page);

        return $items;
    }

    /**
     * Get inventory items with a lock for stock transfer.
     *
     * @param array $data
     * @return \Illuminate\Support\Collection
     */
    public function getItemsWithLock(array $data)
    {
        $items = InventoryItem::query()->with(['stocks' => function ($query) {
            $query->lockForUpdate();
        }])->lockForUpdate()->whereIn('id', array_column($data['items'], 'inventoryItemId'))
            ->get();

        return $items;
    }

    /**
     * Get an inventory item by ID with a lock for stock transfer.
     *
     * @param int $id
     * @param int $fromWarehouseId
     * @param int $toWarehouseId
     * @return InventoryItem|null
     */
    public function getItemByIdWithLock(int $id, int $fromWarehouseId, ?int $toWarehouseId = null): ?InventoryItem
    {
        return InventoryItem::query()->with(['stocks' => function ($query) use ($fromWarehouseId, $toWarehouseId) {
            $query->whereIn('warehouse_id', [$fromWarehouseId, $toWarehouseId])
                ->lockForUpdate();
        }])->lockForUpdate()->find($id);
    }

}

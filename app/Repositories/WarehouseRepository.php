<?php
namespace App\Repositories;

use App\Models\Warehouse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class WarehouseRepository
{
    /**
     * Create a new repository instance.
     * @param Warehouse $model
     */
    public function __construct(public Warehouse $model)
    {}

    /**
     * Paginate inventory items for a specific warehouse.
     *
     * @param int $warehouseId
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function paginate(int $page, int $perPage, $filter): LengthAwarePaginator
    {
        $columns = [
            '*',
        ];

        $query = Warehouse::query();
        return $query->select($columns)
            ->filter($filter)
            ->with('inventoryItems')
            ->paginate(
                $perPage,
                $columns,
                'page',
                $page
            );

    }

    /**
     * Get inventory for a specific warehouse.
     *
     * @param int $page
     * @param int $perPage
     * @param int $warehouseId
     * @return \Illuminate\Support\Collection
     */
    public function getInventory(int $page, int $perPage, int $warehouseId)
    {
        return Cache::remember("warehouse_inventory_{$warehouseId}_page_{$page}_perPage_{$perPage}", 3600, function () use ($warehouseId, $page, $perPage) {
            $warehouse = Warehouse::findOrFail($warehouseId);
            $stocks    = $warehouse->with('stocks')->paginate($perPage, ['*'], 'page', $page);
            return $stocks;
        });

    }
}

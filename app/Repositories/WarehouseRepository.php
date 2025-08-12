<?php
namespace App\Repositories;

use App\Models\Warehouse;
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

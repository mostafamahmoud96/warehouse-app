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

    public function getInventory(int $id)
    {
        $perPage = 10; // Default items per page
        $page = 1; // Default page number
        return Cache::remember("warehouse_inventory_{$id}_page_{$page}_perPage_{$perPage}", 3600, function () use ($id, $page, $perPage) {
            $warehouse = Warehouse::findOrFail($id);

            // Paginate the stocks relationship
            $stocks = $warehouse->with('stocks')->paginate($perPage, ['*'], 'page', $page);
            return $stocks;
        });

    }
}

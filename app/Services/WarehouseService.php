<?php
namespace App\Services;

use App\Http\Filters\Filter;
use App\Repositories\WarehouseRepository;

class WarehouseService
{
    public function __construct(public WarehouseRepository $warehouseRepository)
    {
    }

    /**
     * Paginate inventory items for a specific warehouse.
     *
     * @param int $page
     * @param int $perPage
     * @param Filter $filter
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function paginate(int $page, int $perPage, Filter $filter)
    {
        return $this->warehouseRepository->paginate($page, $perPage, $filter);
    }

    /**
     * Get inventory for a specific warehouse.
     *
     * @param int $id
     * @return \Illuminate\Support\Collection
     */
    public function getInventory(int $page, int $perPage, int $warehouseId)
    {
        return $this->warehouseRepository->getInventory($page, $perPage, $warehouseId);
    }
}

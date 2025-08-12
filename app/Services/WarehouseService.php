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
     * Get inventory for a specific warehouse.
     *
     * @param int $id
     * @return \Illuminate\Support\Collection
     */
    public function getInventory(int $page, int $perPage, int $warehouseId, Filter $filter)
    {
        return $this->warehouseRepository->getInventory($page, $perPage, $warehouseId, $filter);
    }
}

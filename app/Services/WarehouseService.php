<?php
namespace App\Services;

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
    public function getInventory(int $page, int $perPage, int $warehouseId)
    {
        return $this->warehouseRepository->getInventory($page, $perPage, $warehouseId);
    }
}

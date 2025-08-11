<?php
namespace App\Services;

use App\Http\Filters\Filter;
use App\Repositories\InventoryItemRepository;

class InventoryItemService
{
    public function __construct(public InventoryItemRepository $inventoryItemRepository)
    {
    }

    public function index(int $page, int $perPage, Filter $filter)
    {
        return $this->inventoryItemRepository->index($page, $perPage, $filter);
    }

    public function getItemsWithLock(array $productIds)
    {
        return $this->inventoryItemRepository->getItemsWithLock($productIds);
    }

    public function getItemByIdWithLock(int $itemId, int $fromWarehouseId, ?int $toWarehouseId = null)
    {
        return $this->inventoryItemRepository->getItemByIdWithLock($itemId, $fromWarehouseId, $toWarehouseId);
    }
}

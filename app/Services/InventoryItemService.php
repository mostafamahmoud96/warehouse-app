<?php
namespace App\Services;

use App\Http\Filters\Filter;
use App\Repositories\InventoryItemRepository;

class InventoryItemService
{
    public function __construct(public InventoryItemRepository $inventoryItemRepository)
    {
    }

    /**
     * Get paginated inventory items with optional filters.
     *
     * @param int $page
     * @param int $perPage
     * @param Filter $filter
     * @return mixed
     */
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

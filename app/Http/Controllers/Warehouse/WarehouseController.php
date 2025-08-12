<?php
namespace App\Http\Controllers\Warehouse;

use App\Models\Warehouse;
use App\Util\PaginationUtil;
use App\Services\WarehouseService;
use App\Http\Controllers\Controller;
use App\Http\Dto\ListItemsRequestData;
use App\Http\Filters\InventoryItemFilter;
use App\Http\Resources\WarehousePaginateResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class WarehouseController extends Controller
{
    use AuthorizesRequests;
    /**
     * @param WarehouseService $warehouseService
     */
    public function __construct(public WarehouseService $warehouseService)
    {}

    /**
     * Get inventory for a specific warehouse.
     *
     * @param int $id
     * @return \Illuminate\Http\Resources\Json\WarehousePaginateResource
     */
    public function inventory(ListItemsRequestData $data, int $warehouseId, InventoryItemFilter $filter)
    {
        $this->authorize('list', Warehouse::class);

        $data = $this->warehouseService->getInventory(
            $data->page ?? PaginationUtil::PAGE,
            $data->limit ?? PaginationUtil::LIMIT,
            $warehouseId,
            $filter
        );

        return WarehousePaginateResource::collection($data);
    }
}

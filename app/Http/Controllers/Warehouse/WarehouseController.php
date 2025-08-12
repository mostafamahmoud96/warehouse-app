<?php
namespace App\Http\Controllers\Warehouse;

use App\Models\Warehouse;
use App\Util\PaginationUtil;
use Illuminate\Http\Request;
use App\Services\WarehouseService;
use App\Http\Controllers\Controller;
use App\Http\Dto\ListItemsRequestData;
use App\Http\Filters\InventoryItemFilter;
use App\Http\Resources\WarehouseResource;
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
    public function inventory(Request $request, int $warehouseId)
    {
        $this->authorize('list', Warehouse::class);

        $data = $this->warehouseService->getInventory(
            $request->get('page', PaginationUtil::PAGE),
            $request->get('limit', PaginationUtil::LIMIT),
            $warehouseId
        );

        return WarehousePaginateResource::collection($data);
    }

    /**
     * Paginate inventory items for a specific warehouse.
     *
     * @param ListItemsRequestData $listData
     * @param InventoryItemFilter $filter
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function paginate(ListItemsRequestData $listData, InventoryItemFilter $filter)
    {
        $data = $this->warehouseService->paginate(
            $listData->page ?? PaginationUtil::PAGE,
            $listData->per_page ?? PaginationUtil::LIMIT,
            $filter
        );

        return WarehouseResource::collection($data);
    }

}

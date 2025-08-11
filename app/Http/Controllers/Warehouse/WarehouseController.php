<?php
namespace App\Http\Controllers\Warehouse;

use App\ENUMS\PaginationEnum;
use App\Http\Controllers\Controller;
use App\Http\Dto\ListItemsRequestData;
use App\Http\Filters\InventoryItemFilter;
use App\Http\Resources\WarehousePaginateResource;
use App\Http\Resources\WarehouseResource;
use App\Models\Warehouse;
use App\Services\WarehouseService;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{

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
        $data = $this->warehouseService->getInventory(
            $request->get('page', PaginationEnum::PAGE),
            $request->get('limit', PaginationEnum::LIMIT),
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
            $listData->page ?? PaginationEnum::PAGE,
            $listData->per_page ?? PaginationEnum::LIMIT,
            $filter
        );

        return WarehouseResource::collection($data);
    }

}

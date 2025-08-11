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

class WarehouseController extends Controller
{

    /**
     * @param WarehouseService $warehouseService
     */
    public function __construct(public WarehouseService $warehouseService)
    {}

    public function inventory($id)
    {
        $data = $this->warehouseService->getInventory($id);
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

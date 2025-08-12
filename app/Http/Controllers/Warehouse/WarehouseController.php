<?php
namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Http\Dto\ListItemsRequestData;
use App\Http\Resources\WarehousePaginateResource;
use App\Models\Warehouse;
use App\Services\WarehouseService;
use App\Util\PaginationUtil;
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
    public function inventory(ListItemsRequestData $data, int $warehouseId)
    {
        $this->authorize('list', Warehouse::class);

        $data = $this->warehouseService->getInventory(
            $data->page ?? PaginationUtil::PAGE,
            $data->limit ?? PaginationUtil::LIMIT,
            $warehouseId
        );

        return WarehousePaginateResource::collection($data);
    }
}

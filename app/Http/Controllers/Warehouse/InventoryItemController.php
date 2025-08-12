<?php
namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Http\Dto\ListItemsRequestData;
use App\Http\Filters\InventoryItemFilter;
use App\Http\Resources\InventoryItemResource;
use App\Models\InventoryItem;
use App\Services\InventoryItemService;
use App\Util\PaginationUtil;
use Illuminate\Http\Request;

class InventoryItemController extends Controller
{
    /**
     * @param InventoryItemService $inventoryItemService
     */
    public function __construct(public InventoryItemService $inventoryItemService)
    {}

    /**
     * paginated list of inventory per warehouse
     *
     * @param Request $request
     * @param InventoryItemFilter $filter
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(ListItemsRequestData $data, InventoryItemFilter $filter)
    {
        $data = $this->inventoryItemService->index(
            $data->page ?? PaginationUtil::PAGE,
            $data->limit ?? PaginationUtil::LIMIT,
            $filter
        );

        return InventoryItemResource::collection($data);
    }
}

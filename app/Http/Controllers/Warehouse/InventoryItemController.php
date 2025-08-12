<?php
namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
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
    public function index(Request $request, InventoryItemFilter $filter)
    {
        $data = $this->inventoryItemService->index(
            $request->get('page', PaginationUtil::PAGE),
            $request->get('limit', PaginationUtil::LIMIT),
            $filter
        );

        return InventoryItemResource::collection($data);
    }
}

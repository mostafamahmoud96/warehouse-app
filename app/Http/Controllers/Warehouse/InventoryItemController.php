<?php
namespace App\Http\Controllers\Warehouse;

use App\ENUMS\PaginationEnum;
use App\Http\Controllers\Controller;
use App\Http\Filters\InventoryItemFilter;
use App\Http\Resources\InventoryItemResource;
use App\Models\InventoryItem;
use App\Services\InventoryItemService;
use Illuminate\Http\Request;

class InventoryItemController extends Controller
{
    /**
     * @param InventoryItemService $inventoryItemService
     */
    public function __construct(public InventoryItemService $inventoryItemService)
    {}

    public function index(Request $request, InventoryItemFilter $filter)
    {
        $data = $this->inventoryItemService->index(
            $request->get('page', PaginationEnum::PAGE),
            $request->get('limit', PaginationEnum::LIMIT),
            $filter
        );

        return InventoryItemResource::collection($data);
    }
}

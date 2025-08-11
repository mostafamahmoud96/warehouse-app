<?php
namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateStockRequest;
use App\Http\Resources\InventoryItemResourceCollection;
use App\Services\StockService;

class StockController extends Controller
{
    /**
     * @param StockService $stockService
     */
    public function __construct(public StockService $stockService)
    {}

    /**
     * Update stock for a warehouse.
     *
     * @param UpdateStockRequest $request
     * @return InventoryItemResourceCollection
     */
    public function updateStock(UpdateStockRequest $request)
    {
        $data = $this->stockService->AddEditStock($request->validated());
        return InventoryItemResourceCollection::make($data);
    }
}

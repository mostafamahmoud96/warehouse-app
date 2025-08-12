<?php
namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateStockRequest;
use App\Http\Resources\InventoryItemResourceCollection;
use App\Services\StockService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class StockController extends Controller
{
    use AuthorizesRequests;
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
        $this->authorize('update', Stock::class);
    
        $data = $this->stockService->AddEditStock($request->validated());
        return response()->json([
            'message' => 'Stock updated successfully',
        ], 200);
    }
}

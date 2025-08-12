<?php
namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateStockTransfeRequest;
use App\Models\StockTransfer;
use App\Services\StockTransferService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class StockTransferController extends Controller
{
    use AuthorizesRequests;
    /**
     * @param StockTransferService $stockTransferService
     */
    public function __construct(public StockTransferService $stockTransferService)
    {}

    public function stockTransfer(CreateStockTransfeRequest $request)
    {
        $this->authorize('update', StockTransfer::class);
        $this->stockTransferService->stockTransfer($request->validated());

        return response()->json([
            'message' => 'Stock transfer updated successfully',
        ], 200);
    }
}

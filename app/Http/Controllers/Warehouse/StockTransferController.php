<?php
namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateStockTransfeRequest;
use App\Services\StockTransferService;

class StockTransferController extends Controller
{
    /**
     * @param StockTransferService $stockTransferService
     */
    public function __construct(public StockTransferService $stockTransferService)
    {}

    public function stockTransfer(CreateStockTransfeRequest $request)
    {
        $transfers = $this->stockTransferService->stockTransfer($request->validated());

        return response()->json($transfers, 200);
    }
}

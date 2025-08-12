<?php
namespace App\Services;

use App\Models\Stock;
use App\Exceptions\StockEmpty;
use App\Events\LowStockDetected;
use App\Repositories\StockRepository;
use App\Util\StockTransactionTypeUtil;
use App\Exceptions\CantDecreaseStockBelowZero;

class StockService
{
    public function __construct(public StockRepository $stockRepository,
        public InventoryItemService $inventoryItemService) {
    }

    /**
     * Check if there is sufficient stock for the transfer.
     *
     * @param int $itemId
     * @param int $desiredQuantity
     * @param int $fromWarehouseId
     * @return \Illuminate\Support\Collection
     */
    public function checkInSufficentStock(int $itemId, int $desiredQuantity, int $fromWarehouseId)
    {
        $inSufficientQuantity = collect();
        $item                 = $this->inventoryItemService->getItemByIdWithLock($itemId, $fromWarehouseId);

        if ($item->stocks->isEmpty()) {
            $inSufficientQuantity->push([
                'item'        => $item->name,
                'quantity'    => 0,
                'newQuantity' => $desiredQuantity,
                'message'     => 'No stock available in the specified warehouse.',
            ]);

            throw new StockEmpty($inSufficientQuantity);
        }

        foreach ($item->stocks as $stock) {
            $existedQuantity = $stock->pivot->quantity;
            $difference      = $existedQuantity - $desiredQuantity;

            if ($difference < 0 || is_null($stock)) {
                $inSufficientQuantity->push([
                    'diff'        => $difference,
                    'quantity'    => $existedQuantity,
                    'item'        => $item->name,
                    'newQuantity' => $desiredQuantity,
                ]);
            }
        }

        return $inSufficientQuantity;

    }

    /**
     * Update stock level after transfer.
     *
     * @param int $itemId
     * @param int $quantity
     */
    public function updateStock(int $itemId, int $quantity, int $fromWarehouseId, int $toWarehouseId): void
    {
        $alertedQuantities = collect();
        $item              = $this->inventoryItemService->getItemByIdWithLock($itemId, $fromWarehouseId, $toWarehouseId);

        $fromStock = $item->stocks()->where('warehouse_id', $fromWarehouseId)->first();
        if ($fromStock) {
            $newQuantity = $fromStock->pivot->quantity - $quantity;

            $item->stocks()->updateExistingPivot($fromWarehouseId, [
                'quantity' => $newQuantity,
            ]);

            if (($newQuantity) < config('stock.low_stock_threshold') && ! $fromStock->pivot->is_alerted) {
                $alertedQuantities->push($fromStock);
            }
        }

        $toStock = $item->stocks()->where('warehouse_id', $toWarehouseId)->first();

        if ($toStock) {
            $toStock->pivot->quantity += $quantity;
            $item->stocks()->updateExistingPivot($toStock->id, [
                'quantity' => $toStock->pivot->quantity,
            ]);
        } else {
            $item->stocks()->attach($toWarehouseId, [
                'quantity' => $quantity,
            ]);
        }

        if ($alertedQuantities->isNotEmpty()) {
            event(new LowStockDetected($alertedQuantities));
        }

    }

    public function AddEditStock(array $data)
    {
        $warehouseId = $data['warehouseId'];

        foreach ($data['items'] as $item) {
            $itemLocked = $this->inventoryItemService->getItemByIdWithLock($item['inventoryItemId'], $warehouseId);
            $stock      = $itemLocked->stocks()->where('warehouse_id', $warehouseId)->first();

            if ($stock) {
                $newQuantity     = $stock->pivot->quantity + $item['quantity'];
                $currentQuantity = $stock->pivot->quantity ?? 0;
                if ($item['transactionType'] == StockTransactionTypUtil::INCREASE) {
                    $newQuantity = $currentQuantity + $item['quantity'];

                } elseif ($item['transactionType'] == StockTransactionTypeUtil::DECREASE) {
                    $newQuantity = $currentQuantity - $item['quantity'];
                    if ($newQuantity < 0) {
                        throw new CantDecreaseStockBelowZero();
                    }
                }

                $itemLocked->stocks()->updateExistingPivot($warehouseId, [
                    'quantity'   => $newQuantity,
                    'updated_at' => now(),
                ]);
            } else {
                $quantity = $item['quantity'];
                if ($item['transactionType'] == StockTransactionTypeUtil::DECREASE) {
                    $quantity = max(0, $quantity);
                }

                $itemLocked->stocks()->attach($warehouseId, [
                    'quantity'   => $quantity,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

        }

        return $itemLocked->stocks()->where('warehouse_id', $warehouseId)->get();
    }
}

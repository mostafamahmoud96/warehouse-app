<?php
namespace App\Services;

use App\Exceptions\InsufficientQuantity;
use App\Repositories\StockTransferRepository;
use App\Services\InventoryItemService;
use App\Services\StockService;
use Illuminate\Support\Facades\DB;

class StockTransferService
{
    public function __construct(public StockTransferRepository $stockTransferRepository)
    {
    }

    public function stockTransfer(array $data)
    {
        return DB::transaction(function () use ($data) {
            $items = app(InventoryItemService::class)->getItemsWithLock($data);

            $inSufficientItems = collect();
            foreach ($items as $key => $item) {
                $inSufficientStock = app(StockService::class)->checkInSufficentStock($item->id, $data['items'][$key]['quantity'], $data['fromWarehouseId']);
                if ($inSufficientStock->isNotEmpty()) {
                    $inSufficientItems->push($inSufficientStock);
                }
            }

            if ($inSufficientItems->isNotEmpty()) {
                throw new InsufficientQuantity($inSufficientItems);
            }

            $transfers = $this->transferItemsToWarehouse($data);

            foreach ($transfers as $key => $transfer) {
                app(StockService::class)->updateStock(
                    $transfer['inventory_item_id'],
                    $transfer['quantity'],
                    $transfer['from_warehouse_id'],
                    $transfer['to_warehouse_id'],
                );

            }
            return $transfers;
        });

    }

    /**
     * Transfer items to a specific warehouse.
     *
     * @param array $data
     * @return \Illuminate\Support\Collection
     */
    public function transferItemsToWarehouse(array $data)
    {
        return $this->stockTransferRepository->transferItemsToWarehouse($data);
    }

}

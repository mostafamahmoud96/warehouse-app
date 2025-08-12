<?php
namespace App\Repositories;

use Carbon\Carbon;
use App\Models\StockTransfer;

class StockTransferRepository
{
    /**
     * Create a new repository instance.
     * @param StockTransfer $model
     */
    public function __construct(public StockTransfer $model)
    {}

    public function transferItemsToWarehouse(array $data)
    {
        $items = $data['items'];

        $dataToSync = [];
        foreach ($items as $item) {
            $dataToSync[$item['inventoryItemId']] = ['quantity' => $item['quantity']];
        }

        $transfers = [];
        foreach ($dataToSync as $inventoryItemId => $quantity) {
            $transfer = $this->model->updateOrCreate(
                [
                    'from_warehouse_id' => $data['fromWarehouseId'],
                    'to_warehouse_id'   => $data['toWarehouseId'],
                    'inventory_item_id' => $inventoryItemId,
                ],
                [
                    'quantity'      => $quantity['quantity'],
                    'transfer_date' => Carbon::now()->toDateTimeString(),
                ]
            );
            $transfers[] = $transfer->toArray();
        }

        return $transfers;
    }
}

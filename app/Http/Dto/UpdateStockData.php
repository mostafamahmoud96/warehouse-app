<?php
namespace App\Http\Dto;

use Spatie\LaravelData\Data;

class UpdateStockData extends Data
{
    public function __construct(
        public int $warehouseId,
        public array $items
    ) {
    }
    public function toArray(): array
    {
        return [
            'warehouse_id' => $this->warehouseId,
            'items'        => $this->items,
        ];
    }
}

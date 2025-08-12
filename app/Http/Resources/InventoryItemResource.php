<?php
namespace App\Http\Resources;

use App\Http\Resources\WarehouseResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InventoryItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'created_at' => $this->created_at->toDateTimeString(),
            'price'      => $this->price,
            'warehouses' => WarehouseResource::collection($this->whenLoaded('stocks')),
        ];
    }
}

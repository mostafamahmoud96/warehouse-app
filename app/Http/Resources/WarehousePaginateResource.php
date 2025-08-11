<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\InventoryItemDetailsResource;

class WarehousePaginateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'       => $this->id,
            'name'     => $this->name,
            'location' => $this->location,
            'iventoryItems' => InventoryItemDetailsResource::collection($this->whenLoaded('stocks')),
        ];
    }
}

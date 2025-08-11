<?php
namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class InventoryItemFilter extends Filter
{
    /**
     * Filter by name.
     * @param string $value
     * @return Builder
     */
    public function name(string $value): Builder
    {
        return $this->builder->where('name', 'like', '%' . $value . '%');
    }

    /**
     *  Filter by warehouse ID.
     * @param int $warehouseId
     * @return Builder
     */
    public function warehouseId(int $warehouseId): Builder
    {
        return $this->builder->whereHas('stocks', function (Builder $query) use ($warehouseId) {
            $query->where('warehouse_id', $warehouseId);
        });
    }

    /**
     * Filter by price range.
     * @param array $value
     * @return Builder
     */
    public function priceRange(array $value): Builder
    {
        if (isset($value['min'])) {
            $this->builder->where('price', '>=', $value['min']);
        }
        if (isset($value['max'])) {
            $this->builder->where('price', '<=', $value['max']);
        }

        return $this->builder;
    }

    /**
     * Sort the resources by the given order and field.
     *
     * @param array $value
     * @return Builder
     */
    public function sort(array $value = []): Builder
    {
        if (! isset($value['by'])) {
            return $this->builder;
        }

        if ($value['by'] == 'name') {
            return $this->builder->orderBy('inventory_items.name', $value['order'] ?? 'desc');
        } elseif ($value['by'] == 'price') {
            return $this->builder->orderBy('inventory_items.price', $value['order'] ?? 'desc');
        } elseif ($value['by'] == 'createdAt') {
            return $this->builder->orderBy('inventory_items.created_at', $value['order'] ?? 'desc');
        } elseif (isset($value['by']) && ! Schema::hasColumn('inventory_items', $value['by'])) {
            return $this->builder;
        }

        return $this->builder->orderBy(
            $value['by'] ?? 'inventory_items', $value['order'] ?? 'desc'
        );
    }
}

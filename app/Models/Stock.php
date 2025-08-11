<?php
namespace App\Models;

use App\Models\Warehouse;
use App\Models\StockAlert;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = ['warehouse_id', 'inventory_item_id', 'quantity','is_alerted'];

    public function warehouse(): BelongsTo
    {
        dd("
        AAAAAAAAAAAAAAAAAAAAA");
        return $this->belongsTo(Warehouse::class);
    }

    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    /**
     * The alerts that belong to the ingredient.
     * @return BelongsToMany
     */
    public function alerted(): HasOne
    {
        return $this->hasOne(StockAlert::class, 'stock_id');
    }
}

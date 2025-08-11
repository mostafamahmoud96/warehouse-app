<?php
namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory, Filterable;

    protected $fillable = ['name', 'location'];

    public function stocks()
    {
        return $this->belongsToMany(InventoryItem::class, 'stocks')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    /**
     * Stock transfers originating from this warehouse.
     */
    public function outgoingStockTransfers()
    {
        return $this->hasMany(StockTransfer::class, 'from_warehouse_id');
    }

    /**
     * Stock transfers destined for this warehouse.
     */
    public function incomingStockTransfers()
    {
        return $this->hasMany(StockTransfer::class, 'to_warehouse_id');
    }

}

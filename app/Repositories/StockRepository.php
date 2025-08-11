<?php
namespace App\Repositories;

use App\Models\Stock;

class StockRepository
{
    /**
     * Create a new repository instance.
     * @param Stock $model
     */
    public function __construct(public Stock $model)
    {
    }

}

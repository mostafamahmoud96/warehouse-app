<?php
namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class StockEmpty extends Exception
{
    public function __construct(private Collection $inSufficientStocks)
    {}

    public function render()
    {
        $errors = $this->inSufficientStocks->map(function ($stock) {
            return "Stock {$stock['item']} has no stock in the specified warehouse.";
        })->all();

        return response()->json([
            'success' => false,
            'message' => 'No Stock Available',
            'errors'  => $errors,
        ], Response::HTTP_OK);
    }
}

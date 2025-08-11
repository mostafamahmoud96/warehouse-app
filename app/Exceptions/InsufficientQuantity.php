<?php
namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class InsufficientQuantity extends Exception
{
    public function __construct(private Collection $inSufficientItems)
    {}

    public function render()
    {
        $errors = $this->inSufficientItems->map(function ($item) {
            return $item->map(function ($stock) {
                return "Stock {$stock['item']} requires more Quantity which is " . abs($stock['diff']) . " GM for {$stock['quantity']}";
            });
        })->all();
        return response()->json([
            'success' => false,
            'message' => 'Insufficient quantity in stock',
            'errors'  => $errors,
        ], Response::HTTP_OK);
    }
}

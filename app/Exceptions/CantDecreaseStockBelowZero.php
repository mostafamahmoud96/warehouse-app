<?php
namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class CantDecreaseStockBelowZero extends Exception
{
    public function __construct()
    {
        parent::__construct('Cannot decrease stock below zero', Response::HTTP_BAD_REQUEST);
    }
}

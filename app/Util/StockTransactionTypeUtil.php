<?php
namespace App\Util;

class StockTransactionTypeUtil
{
    const INCREASE = 'increase';
    const DECREASE = 'decrease';

    public static function getValues(): array
    {
        return [
            self::INCREASE,
            self::DECREASE,
        ];
    }
}

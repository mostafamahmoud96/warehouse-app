<?php
namespace App\ENUMS;

class StockTransactionTypeEnum
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

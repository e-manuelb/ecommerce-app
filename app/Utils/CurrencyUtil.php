<?php

namespace App\Utils;

use NumberFormatter;

class CurrencyUtil
{
    public static function formatBRL(float $price): string
    {
        return (new NumberFormatter('pt_BR',  NumberFormatter::CURRENCY))->format($price, NumberFormatter::TYPE_DEFAULT);
    }
}

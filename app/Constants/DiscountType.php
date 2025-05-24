<?php

namespace App\Constants;

class DiscountType
{
    public const AMOUNT = 'AMOUNT';
    public const PERCENTAGE = 'PERCENTAGE';
    public const ALL = [
        self::AMOUNT,
        self::PERCENTAGE
    ];

    public static function translate(string $type): string
    {
        return match ($type) {
            self::AMOUNT => 'VALOR',
            self::PERCENTAGE => 'PORCENTAGEM',
            default => $type,
        };
    }
}

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
}

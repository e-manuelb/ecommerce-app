<?php

namespace App\Constants;

class DiscountTypes
{
    public const AMOUNT = 'AMOUNT';
    public const PERCENTAGE = 'PERCENTAGE';

    public const ALL = [
        self::AMOUNT,
        self::PERCENTAGE
    ];
}

<?php

namespace App\Constants;

class ProductTypes
{
    public const PRODUCT = 'NORMAL';
    public const PRODUCT_VARIATION = 'VARIATION';
    public const ALL = [
        self::PRODUCT,
        self::PRODUCT_VARIATION,
    ];
}

<?php

namespace App\Constants;

class ProductType
{
    public const PRODUCT = 'NORMAL';
    public const PRODUCT_VARIATION = 'VARIATION';
    public const ALL = [
        self::PRODUCT,
        self::PRODUCT_VARIATION,
    ];
}

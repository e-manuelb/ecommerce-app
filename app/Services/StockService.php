<?php

namespace App\Services;

use App\Models\Stock;

readonly class StockService
{
    public function create(array $data): Stock
    {
        return Stock::query()->create($data);
    }
}

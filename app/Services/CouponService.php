<?php

namespace App\Services;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CouponService
{
    public function paginate(): LengthAwarePaginator
    {
        return Coupon::query()->paginate();
    }

    public function create(array $data): Coupon {
        return Coupon::query()->create($data);
    }
}

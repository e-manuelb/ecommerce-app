<?php

namespace App\Services;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class CouponService
{
    public function paginate(): LengthAwarePaginator
    {
        return Coupon::query()->paginate();
    }

    public function findByCode(string $code): ?Coupon
    {
        return Coupon::query()->where('code', $code)->first();
    }

    public function create(array $data): Coupon
    {
        return Coupon::query()->create($data);
    }
}

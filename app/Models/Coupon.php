<?php

namespace App\Models;

use App\Constants\DiscountTypes;
use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use NumberFormatter;

/**
 * @property int $id
 * @property string $uuid
 * @property string $code
 * @property float $discount
 * @property string $discount_type
 * @property bool $active
 * @property mixed $min_subtotal_to_apply
 * @property mixed $expires_at
 * @property mixed $created_at
 * @property mixed $updated_at
 */
class Coupon extends Model
{
    use HasFactory, HasUUID;

    public $table = 'coupons';
    protected $fillable = [
        'uuid',
        'code',
        'discount',
        'discount_type',
        'active',
        'min_subtotal_to_apply',
        'expires_at',
    ];

    public static function formatByDiscountType(float $discount, string $type): string
    {
        return match ($type) {
            DiscountTypes::AMOUNT => (new NumberFormatter('pt_BR', NumberFormatter::CURRENCY))->format($discount, NumberFormatter::TYPE_DEFAULT),
            DiscountTypes::PERCENTAGE => $discount . '%',
            default => $discount,
        };
    }
}

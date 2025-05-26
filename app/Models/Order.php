<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $uuid
 * @property float $subtotal
 * @property float $total
 * @property string $status
 * @property OrderItem[] $orderItems
 * @property string $additional_information
 * @property OrderAddress $orderAddress
 */
class Order extends Model
{
    use HasUUID;

    public $table = 'orders';
    protected $fillable = [
        'uuid',
        'subtotal',
        'total',
        'status',
        'additional_information',
    ];

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orderAddress(): HasOne {
        return $this->hasOne(OrderAddress::class);
    }
}

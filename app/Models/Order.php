<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $uuid
 * @property integer $user_id
 * @property float $total
 * @property User $user
 * @property OrderItem[] $orderItems
 */
class Order extends Model
{
    public $table = 'orders';
    protected $fillable = [
        'uuid',
        'user_id',
        'total'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}

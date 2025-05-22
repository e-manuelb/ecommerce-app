<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $uuid
 * @property integer $order_id
 * @property integer $quantity
 * @property float $price
 * @property string $product_type
 * @property string $product_reference_uuid
 * @property mixed $created_at
 * @property mixed $updated_at
 */
class OrderItem extends Model
{
    public $table = 'order_items';
    protected $fillable = [
        'uuid',
        'order_id',
        'quantity',
        'price',
        'product_type',
        'product_reference_uuid'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}

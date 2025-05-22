<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $uuid
 * @property int $quantity
 * @property string $product_type
 * @property string $product_reference_uuid
 * @property Product $product
 * @property ProductVariation $productVariation
 * @property mixed $created_at
 * @property mixed $updated_at
 */
class Stock extends Model
{
    use HasUUID;

    public $table = 'stocks';
    protected $fillable = [
        'uuid',
        'quantity',
        'product_type',
        'product_reference_uuid'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_reference_uuid', 'uuid');
    }

    public function productVariation(): BelongsTo
    {
        return $this->belongsTo(ProductVariation::class, 'product_reference_uuid', 'uuid');
    }
}

<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $uuid
 * @property int $product_id
 * @property string $name
 * @property string $sku
 * @property string $description
 * @property float $price
 * @property Product $product
 * @property Stock $stock
 * @property mixed $deleted_at
 * @property mixed $created_at
 * @property mixed $updated_at
 */
class ProductVariation extends Model
{
    use HasUUID;

    protected $table = 'product_variations';
    protected $fillable = [
        'uuid',
        'product_id',
        'name',
        'sku',
        'description',
        'price',
    ];

    public function product(): BelongsTo {
        return $this->belongsTo(Product::class);
    }

    public function stock(): HasOne
    {
        return $this->hasOne(Stock::class, 'product_reference_uuid', 'uuid');
    }
}

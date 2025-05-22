<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;
use NumberFormatter;

/**
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property string $sku
 * @property string $description
 * @property float $price
 * @property ProductVariation[] $productVariations
 * @property Stock $stock
 * @property mixed $deleted_at
 * @property mixed $created_at
 * @property mixed $updated_at
 */
class Product extends Model
{
    use HasFactory, HasUUID;

    protected $table = 'products';
    protected $fillable = [
        'uuid',
        'name',
        'sku',
        'description',
        'price',
    ];

    protected static function booted(): void
    {
        static::creating(function ($product) {
            $product->uuid = Str::uuid()->toString();
        });
    }

    public function productVariations(): HasMany
    {
        return $this->hasMany(ProductVariation::class);
    }

    public function stock(): HasOne
    {
        return $this->hasOne(Stock::class, 'product_reference_uuid', 'uuid');
    }
}

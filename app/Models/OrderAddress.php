<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $uuid
 * @property int $order_id
 * @property string $zip_code
 * @property string $street
 * @property string $number
 * @property string $complement
 * @property string $neighborhood
 * @property string $city
 * @property string $state
 * @property string $country
 * @property mixed $created_at
 * @property mixed $updated_at
 * @property Order $order
 */
class OrderAddress extends Model
{
    use HasUUID;

    protected $fillable = [
        'uuid',
        'order_id',
        'zip_code',
        'street',
        'number',
        'complement',
        'neighborhood',
        'city',
        'state',
        'country',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}

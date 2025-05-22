<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasUUID
{
    protected static function bootHasUuid(): void
    {
        static::creating(function ($model) {
            $model->uuid = $model->uuid ?? Str::uuid()->toString();
        });
    }
}

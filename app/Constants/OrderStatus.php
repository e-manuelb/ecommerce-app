<?php

namespace App\Constants;

class OrderStatus
{
    public const PENDING = 'PENDING';
    public const PROCESSING = 'PROCESSING';
    public const CANCELLED = 'CANCELLED';
    public const COMPLETED = 'COMPLETED';
    public const ALL = [
        self::PENDING,
        self::PROCESSING,
        self::CANCELLED,
        self::COMPLETED
    ];

    public static function translate(string $status): string
    {
        return match ($status) {
            self::PENDING => 'PENDENTE',
            self::PROCESSING => 'PROCESSANDO',
            self::CANCELLED => 'CANCELADO',
            self::COMPLETED => 'COMPLETADO',
            default => $status
        };
    }
}

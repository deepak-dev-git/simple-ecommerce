<?php

namespace App\Enums;

class OrderStatus extends Enum{
    public const PENDING = 'Pending';
    public const INPROGRESS = 'Inprogress';
    public const DISPATCHED = 'Dispatched';
    public const COMPLETED = 'Completed';
    public const CANCELLED = 'Cancelled';
    public const REFUNDED = 'Refunded';

    public static function getAll(): array
    {
        return [
            self::PENDING,
            self::INPROGRESS,
            self::DISPATCHED,
            self::COMPLETED,
            self::CANCELLED,
            self::REFUNDED,
        ];
    }
}

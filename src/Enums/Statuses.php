<?php

namespace LaravelLiberu\DataImport\Enums;

use LaravelLiberu\Enums\Services\Enum;

class Statuses extends Enum
{
    public const Waiting = 10;
    public const Processing = 20;
    public const Processed = 23;
    public const ExportingRejected = 26;
    public const Finalized = 30;
    public const Cancelled = 40;

    protected static array $data = [
        self::Waiting => 'waiting',
        self::Processing => 'processing',
        self::Processed => 'processed',
        self::ExportingRejected => 'exporting rejected',
        self::Finalized => 'finalized',
        self::Cancelled => 'cancelled',
    ];

    public static function running(): array
    {
        return [self::Waiting, self::Processing];
    }

    public static function deletable(): array
    {
        return [self::Finalized, self::Cancelled];
    }

    public static function isDeletable(int $status): bool
    {
        return in_array($status, self::deletable());
    }
}

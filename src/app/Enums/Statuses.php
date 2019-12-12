<?php

namespace LaravelEnso\DataImport\app\Enums;

use LaravelEnso\IO\app\Enums\IOStatuses;

class Statuses extends IOStatuses
{
    public const Processed = 23;
    public const ExportingRejected = 26;

    protected static $data = [
        IOStatuses::Waiting => 'Waiting',
        IOStatuses::Processing => 'Processing',
        self::Processed => 'Processed',
        self::ExportingRejected => 'Exporting Rejected',
        IOStatuses::Finalized => 'Finalized',
    ];
}

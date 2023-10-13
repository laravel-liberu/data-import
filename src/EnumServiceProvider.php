<?php

namespace LaravelLiberu\DataImport;

use LaravelLiberu\DataImport\Enums\CssClasses;
use LaravelLiberu\DataImport\Enums\Statuses;
use LaravelLiberu\DataImport\Enums\Types;
use LaravelLiberu\Enums\EnumServiceProvider as ServiceProvider;

class EnumServiceProvider extends ServiceProvider
{
    public $register = [
        'importCssClasses' => CssClasses::class,
        'importStatuses' => Statuses::class,
        'importTypes' => Types::class,
    ];
}

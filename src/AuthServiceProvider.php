<?php

namespace LaravelLiberu\DataImport;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use LaravelLiberu\DataImport\Models\Import;
use LaravelLiberu\DataImport\Policies\Policy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Import::class => Policy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}

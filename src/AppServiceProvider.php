<?php

namespace LaravelLiberu\DataImport;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use LaravelLiberu\DataImport\Commands\Purge;
use LaravelLiberu\DataImport\Models\Import;
use LaravelLiberu\IO\Observers\IOObserver;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Import::observe(IOObserver::class);

        $this->load()
            ->publishAssets()
            ->publishExamples()
            ->command();
    }

    private function load(): self
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');

        $this->mergeConfigFrom(__DIR__.'/../config/imports.php', 'liberu.imports');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-liberu/data-import');

        return $this;
    }

    private function publishAssets(): self
    {
        $this->publishes([
            __DIR__.'/../config' => config_path('liberu'),
        ], ['data-import-config', 'liberu-config']);

        $this->publishes([
            __DIR__.'/../database/factories' => database_path('factories'),
        ], ['data-import-factory', 'liberu-factories']);

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/laravel-liberu/data-import'),
        ], ['data-import-mail', 'liberu-mail']);

        return $this;
    }

    private function publishExamples(): self
    {
        $stubPrefix = __DIR__.'/../stubs/';

        $stubs = Collection::wrap([
            'Imports/Importers/ExampleImporter',
            'Imports/Templates/exampleTemplate',
            'Imports/Validators/CustomValidator',
        ])->reduce(fn ($stubs, $stub) => $stubs
            ->put("{$stubPrefix}{$stub}.stub", app_path("{$stub}.php")), new Collection());

        $this->publishes($stubs->all(), 'data-import-examples');

        return $this;
    }

    private function command(): void
    {
        $this->commands(Purge::class);

        $this->app->booted(fn () => $this->app->make(Schedule::class)
            ->command('liberu:data-import:purge')->daily());
    }
}

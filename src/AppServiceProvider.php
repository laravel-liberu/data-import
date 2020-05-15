<?php

namespace LaravelEnso\DataImport;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use LaravelEnso\DataImport\App\Models\DataImport;
use LaravelEnso\DataImport\App\Models\ImportTemplate;
use LaravelEnso\DataImport\App\Models\RejectedImport;
use LaravelEnso\IO\App\Observers\IOObserver;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        DataImport::observe(IOObserver::class);

        $this->load()
            ->mapMorphs()
            ->publishAssets()
            ->publishExamples();
    }

    private function load()
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        $this->loadRoutesFrom(__DIR__.'/routes/api.php');

        $this->mergeConfigFrom(__DIR__.'/config/imports.php', 'enso.imports');

        $this->loadViewsFrom(__DIR__.'/resources/views', 'laravel-enso/data-import');

        return $this;
    }

    private function mapMorphs()
    {
        Relation::morphMap([
            DataImport::morphMapKey() => DataImport::class,
            ImportTemplate::morphMapKey() => ImportTemplate::class,
            RejectedImport::morphMapKey() => RejectedImport::class,
        ]);

        return $this;
    }

    private function publishAssets()
    {
        $this->publishes([
            __DIR__.'/config' => config_path('enso'),
        ], ['data-import-config', 'enso-config']);

        $this->publishes([
            __DIR__.'/database/factories' => database_path('factories'),
        ], ['data-import-factory', 'enso-factories']);

        $this->publishes([
            __DIR__.'/resources/views' => resource_path('views/vendor/laravel-enso/data-import'),
        ], ['data-import-mail', 'enso-mail']);

        return $this;
    }

    private function publishExamples()
    {
        $stubPrefix = __DIR__.'/../stubs/';

        $publishes = (new Collection([
            'Imports/Importers/ExampleImporter',
            'Imports/Templates/exampleTemplate',
            'Imports/Validators/CustomValidator',
        ]))->reduce(fn ($publishes, $stub) => $publishes
            ->put("{$stubPrefix}{$stub}.stub", app_path("{$stub}.php")), new Collection());

        $this->publishes($publishes->all(), 'data-import-examples');

        return $this;
    }
}

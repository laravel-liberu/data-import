<?php

namespace LaravelLiberu\DataImport\Services\Importers;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use LaravelLiberu\DataImport\Contracts\AfterHook;
use LaravelLiberu\DataImport\Contracts\Authenticates;
use LaravelLiberu\DataImport\Contracts\BeforeHook;
use LaravelLiberu\DataImport\Enums\Statuses;
use LaravelLiberu\DataImport\Jobs\Finalize;
use LaravelLiberu\DataImport\Jobs\RejectedExport;
use LaravelLiberu\DataImport\Jobs\Sheet;
use LaravelLiberu\DataImport\Models\Import as Model;
use LaravelLiberu\DataImport\Services\Template;

class Import
{
    private Template $template;

    public function __construct(
        private Model $import,
        private string $sheet
    ) {
        $this->template = $import->template();
    }

    public function handle(): void
    {
        $this->prepare()
            ->beforeHook()
            ->dispatch();
    }

    private function prepare(): self
    {
        if ($this->import->waiting()) {
            $this->import->update(['status' => Statuses::Processing]);
        }

        return $this;
    }

    private function beforeHook(): self
    {
        $importer = $this->template->importer($this->sheet);

        if ($importer instanceof BeforeHook) {
            if ($importer instanceof Authenticates) {
                Auth::setUser($this->import->createdBy);
            }

            $importer->before($this->import);
        }

        return $this;
    }

    public function dispatch(): self
    {
        $import = $this->import;
        $afterHook = $this->afterHook();
        $nextStep = $this->nextStep();

        $batch = Bus::batch([new Sheet($this->import, $this->sheet)])
            ->onQueue($this->template->queue())
            ->then(fn () => $import->update(['batch' => null]))
            ->then(fn ($batch) => $batch->cancelled() ? null : $afterHook())
            ->then(fn ($batch) => $batch->cancelled() ? null : $nextStep())
            ->name($this->sheet)
            ->dispatch();

        $this->import->update(['batch' => $batch->id]);

        return $this;
    }

    private function afterHook(): Closure
    {
        $importer = $this->template->importer($this->sheet);

        return fn () => $importer instanceof AfterHook
            ? $importer->after($this->import)
            : null;
    }

    public function nextStep(): Closure
    {
        $import = $this->import;
        $sheet = $this->sheet;
        $nextSheet = $this->template->nextSheet($sheet);

        if ($nextSheet) {
            return fn () => $import->import($nextSheet->get('name'));
        }

        return fn () => RejectedExport::withChain([new Finalize($import)])
            ->dispatch($import);
    }
}

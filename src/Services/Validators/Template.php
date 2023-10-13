<?php

namespace LaravelLiberu\DataImport\Services\Validators;

use Illuminate\Support\Collection;
use LaravelLiberu\DataImport\Attributes\Column;
use LaravelLiberu\DataImport\Attributes\Sheet;
use LaravelLiberu\DataImport\Attributes\Template as Attributes;
use LaravelLiberu\DataImport\Contracts\Importable;
use LaravelLiberu\DataImport\Exceptions\Template as Exception;
use LaravelLiberu\Helpers\Services\Obj;

class Template
{
    public function __construct(private Obj $template)
    {
    }

    public function run(): void
    {
        $this->root()
            ->sheets()
            ->columns();
    }

    private function root(): self
    {
        (new Attributes())->validateMandatory($this->template->keys())
            ->rejectUnknown($this->template->keys());

        return $this;
    }

    private function sheets(): self
    {
        $this->template->get('sheets')
            ->each(fn ($sheet) => (new Sheet())
                ->validateMandatory($sheet->keys())
                ->rejectUnknown($sheet->keys()))
            ->each(fn ($sheet) => $this->importer($sheet)
                ->validator($sheet));

        return $this;
    }

    private function importer($sheet): self
    {
        if (! class_exists($sheet->get('importerClass'))) {
            throw Exception::missingImporterClass($sheet);
        }

        $implements = class_implements($sheet->get('importerClass'));
        $underContract = Collection::wrap($implements)->contains(Importable::class);

        if (! $underContract) {
            throw Exception::importerMissingContract($sheet);
        }

        return $this;
    }

    private function validator($sheet): void
    {
        if (! $sheet->has('validatorClass')) {
            return;
        }

        if (! class_exists($sheet->get('validatorClass'))) {
            throw Exception::missingValidatorClass($sheet);
        }

        if (! is_subclass_of($sheet->get('validatorClass'), Validator::class)) {
            throw Exception::incorectValidator($sheet);
        }
    }

    private function columns(): void
    {
        $this->template->get('sheets')
            ->pluck('columns')->each(fn ($columns) => $columns
                ->each(fn ($column) => (new Column())
                    ->validateMandatory($column->keys())
                    ->rejectUnknown($column->keys())));
    }
}

<?php

namespace LaravelLiberu\DataImport\Services;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use LaravelLiberu\DataImport\Models\Import;
use LaravelLiberu\Files\Models\Type;

abstract class ExcelSeeder extends Seeder
{
    public function __construct()
    {
        $this->savedName = "{$this->hash()}.xlsx";
    }

    public function run()
    {
        File::copy($this->source(), Storage::path($this->path()));

        Import::factory()
            ->make(['type' => $this->type(), 'params' => $this->params()])
            ->attach($this->savedName, $this->filename());
    }

    abstract protected function type(): string;

    abstract protected function filename(): string;

    protected function params(): array
    {
        return [];
    }

    private function source(): string
    {
        $path = Config::get('liberu.imports.seederPath');

        return "{$path}/{$this->filename()}";
    }

    private function path(): string
    {
        return Type::for(Import::class)->path($this->savedName);
    }

    private function hash(): string
    {
        return Str::random(40);
    }
}

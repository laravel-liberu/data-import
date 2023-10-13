<?php

namespace LaravelLiberu\DataImport\Services\Importers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use LaravelLiberu\DataImport\Contracts\Authenticates;
use LaravelLiberu\DataImport\Contracts\Authorizes;
use LaravelLiberu\DataImport\Contracts\Importable;
use LaravelLiberu\DataImport\Exceptions\Import as Exception;
use LaravelLiberu\DataImport\Models\Chunk as Model;
use LaravelLiberu\DataImport\Models\Import;
use LaravelLiberu\DataImport\Models\RejectedChunk;
use LaravelLiberu\DataImport\Services\Validators\Row;
use LaravelLiberu\Helpers\Services\Obj;
use Symfony\Component\Console\Output\ConsoleOutput;
use Throwable;

class Chunk
{
    private Import $import;
    private Importable $importer;
    private RejectedChunk $rejectedChunk;
    private ConsoleOutput $output;

    public function __construct(private Model $chunk)
    {
        $this->import = $this->chunk->import;
        $this->importer = $this->chunk->importer();
        $this->rejectedChunk = $this->rejectedChunk();
        $this->output = new ConsoleOutput();
    }

    public function handle(): void
    {
        $this->authenticate()
            ->authorize();

        Collection::wrap($this->chunk->rows)
            ->each(fn ($row) => $this->process($row));

        $this->dumpRejected()
            ->updateProgress();

        $this->chunk->delete();
    }

    private function authenticate(): self
    {
        if ($this->importer instanceof Authenticates) {
            Auth::setUser($this->import->createdBy);
        }

        return $this;
    }

    private function authorize(): void
    {
        $unauthorized = $this->importer instanceof Authorizes
            && ! $this->importer->authorizes($this->import);

        if ($unauthorized) {
            throw Exception::unauthorized();
        }
    }

    private function process(array $row): void
    {
        $rowObj = $this->row($row);
        $validator = new Row($rowObj, $this->chunk);

        if ($validator->passes()) {
            $this->import($rowObj);
        } else {
            $row[] = $validator->errors()->implode(' | ');
            $this->rejectedChunk->add($row);
        }
    }

    private function row(array $row): Obj
    {
        return new Obj(array_combine($this->chunk->header, $row));
    }

    private function import(Obj $row): void
    {
        try {
            $this->importer->run($row, $this->import);
        } catch (Throwable $throwable) {
            $row = $row->values()->toArray();
            $row[] = Config::get('liberu.imports.unknownError');
            $this->rejectedChunk->add($row);

            $error = App::isProduction() || App::runningInConsole()
                ? $throwable->getMessage()
                : "{$throwable->getMessage()} {$throwable->getTraceAsString()}";

            Log::debug($error);

            if (App::runningInConsole()) {
                $this->output->writeln("<error>{$throwable->getMessage()}</error>");
            }
        }
    }

    private function dumpRejected(): self
    {
        if (! $this->rejectedChunk->empty()) {
            $this->rejectedChunk->save();
        }

        return $this;
    }

    private function updateProgress(): void
    {
        $total = $this->chunk->count();
        $failed = $this->rejectedChunk->count();

        DB::transaction(fn () => Import::lockForUpdate()
            ->whereId($this->import->id)->first()
            ->updateProgress($total - $failed, $failed));
    }

    private function rejectedChunk(): RejectedChunk
    {
        return RejectedChunk::factory()->make([
            'import_id' => $this->import->id,
            'sheet' => $this->chunk->sheet,
            'header' => $this->chunk->header,
        ]);
    }
}

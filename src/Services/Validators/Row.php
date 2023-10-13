<?php

namespace LaravelLiberu\DataImport\Services\Validators;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use LaravelLiberu\DataImport\Models\Chunk;
use LaravelLiberu\Helpers\Services\Obj;

class Row
{
    private Collection $errors;

    public function __construct(
        private Obj $row,
        private Chunk $chunk
    ) {
        $this->errors = new Collection();
    }

    public function passes(): bool
    {
        $this->implicit()
            ->custom();

        return $this->errors->isEmpty();
    }

    public function errors(): Collection
    {
        return $this->errors;
    }

    private function implicit(): self
    {
        $rules = $this->chunk->template()->columnRules($this->chunk->sheet);
        $implicit = Validator::make($this->row->all(), $rules);
        $this->errors->push(...$implicit->errors()->all());

        return $this;
    }

    private function custom(): void
    {
        $custom = $this->chunk->template()->customValidator($this->chunk->sheet);

        if ($custom) {
            $custom->run($this->row, $this->chunk->import);
            $this->errors->push(...$custom->errors());
        }
    }
}

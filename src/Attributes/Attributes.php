<?php

namespace LaravelLiberu\DataImport\Attributes;

use Illuminate\Support\Collection;
use LaravelLiberu\DataImport\Exceptions\Attributes as Exception;
use ReflectionClass;

class Attributes
{
    protected array $mandatory = [];
    protected array $optional = [];
    protected array $dependent = [];
    protected array $values = [];

    public function allowed(): Collection
    {
        return $this->mandatory()->concat($this->optional());
    }

    public function validateMandatory(Collection $attributes): self
    {
        $this->mandatory()->diff($attributes)
            ->unlessEmpty(fn ($missing) => throw Exception::missing($missing, $this->class()));

        return $this;
    }

    public function rejectUnknown(Collection $attributes): self
    {
        $attributes->diff($this->allowed())
            ->unlessEmpty(fn ($unknown) => throw Exception::unknown($unknown, $this->class()));

        return $this;
    }

    public function values($type): Collection
    {
        return new Collection($this->values[$type] ?? []);
    }

    public function dependent($type): Collection
    {
        return new Collection($this->dependent[$type] ?? []);
    }

    public function class(): string
    {
        $class = (new ReflectionClass(static::class))->getShortName();

        return strtolower($class);
    }

    protected function mandatory(): Collection
    {
        return new Collection($this->mandatory);
    }

    protected function optional(): Collection
    {
        return new Collection($this->optional);
    }
}

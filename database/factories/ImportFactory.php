<?php

namespace LaravelLiberu\DataImport\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use LaravelLiberu\DataImport\Enums\Statuses;
use LaravelLiberu\DataImport\Models\Import;

class ImportFactory extends Factory
{
    protected $model = Import::class;

    public function definition()
    {
        return [
            'type' => null,
            'batch' => null,
            'params' => [],
            'successful' => 0,
            'failed' => 0,
            'status' => Statuses::Waiting,
        ];
    }
}

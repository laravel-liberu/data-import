<?php

namespace LaravelLiberu\DataImport\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use LaravelLiberu\DataImport\Enums\Statuses;
use LaravelLiberu\DataImport\Models\DataImport;

class DataImportFactory extends Factory
{
    protected $model = DataImport::class;

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

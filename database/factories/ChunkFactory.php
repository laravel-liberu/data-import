<?php

namespace LaravelLiberu\DataImport\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use LaravelLiberu\DataImport\Models\Chunk;

class ChunkFactory extends Factory
{
    protected $model = Chunk::class;

    public function definition()
    {
        return [
            'import_id' => null,
            'sheet' => null,
            'header' => [],
            'rows' => [],
        ];
    }
}

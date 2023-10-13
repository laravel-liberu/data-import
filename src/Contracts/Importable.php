<?php

namespace LaravelLiberu\DataImport\Contracts;

use LaravelLiberu\DataImport\Models\Import;
use LaravelLiberu\Helpers\Services\Obj;

interface Importable
{
    public function run(Obj $row, Import $import);
}

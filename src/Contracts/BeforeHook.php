<?php

namespace LaravelLiberu\DataImport\Contracts;

use LaravelLiberu\DataImport\Models\Import;

interface BeforeHook
{
    public function before(Import $import);
}

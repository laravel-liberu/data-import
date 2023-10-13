<?php

namespace LaravelLiberu\DataImport\Contracts;

use LaravelLiberu\DataImport\Models\Import;

interface AfterHook
{
    public function after(Import $import);
}

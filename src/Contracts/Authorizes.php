<?php

namespace LaravelLiberu\DataImport\Contracts;

use LaravelLiberu\DataImport\Models\Import;

interface Authorizes extends Authenticates
{
    public function authorizes(Import $import): bool;
}

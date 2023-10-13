<?php

namespace LaravelLiberu\DataImport\Http\Controllers\Import;

use Illuminate\Routing\Controller;
use LaravelLiberu\DataImport\Tables\Builders\Import;
use LaravelLiberu\Tables\Traits\Data;

class TableData extends Controller
{
    use Data;

    protected string $tableClass = Import::class;
}

<?php

namespace LaravelLiberu\DataImport\Http\Controllers\Import;

use Illuminate\Routing\Controller;
use LaravelLiberu\DataImport\Tables\Builders\Import;
use LaravelLiberu\Tables\Traits\Excel;

class ExportExcel extends Controller
{
    use Excel;

    protected string $tableClass = Import::class;
}

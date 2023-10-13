<?php

namespace LaravelLiberu\DataImport\Http\Controllers\Import;

use Illuminate\Routing\Controller;
use LaravelLiberu\DataImport\Services\ImportTemplate;
use LaravelLiberu\Excel\Services\ExcelExport;

class Template extends Controller
{
    public function __invoke(string $type)
    {
        return (new ExcelExport(new ImportTemplate($type)))->inline();
    }
}

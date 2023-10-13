<?php

namespace LaravelLiberu\DataImport\Http\Controllers\Import;

use Illuminate\Routing\Controller;
use LaravelLiberu\DataImport\Models\Import;

class Download extends Controller
{
    public function __invoke(Import $import)
    {
        return $import->file->download();
    }
}

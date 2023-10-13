<?php

namespace LaravelLiberu\DataImport\Http\Controllers\Import;

use Illuminate\Routing\Controller;
use LaravelLiberu\DataImport\Models\Import;

class Restart extends Controller
{
    public function __invoke(Import $import)
    {
        $import->restart()->import();

        return ['message' => __('The import was restarted')];
    }
}

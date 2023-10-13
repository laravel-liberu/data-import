<?php

namespace LaravelLiberu\DataImport\Http\Controllers\Import;

use Illuminate\Routing\Controller;
use LaravelLiberu\DataImport\Models\Import;

class Cancel extends Controller
{
    public function __invoke(Import $import)
    {
        $import->cancel();

        return ['message' => __('The import was cancelled successfully')];
    }
}

<?php

namespace LaravelEnso\DataImport\App\Http\Controllers\Import;

use Illuminate\Routing\Controller;
use LaravelEnso\DataImport\App\Enums\ImportTypes;

class Index extends Controller
{
    public function __invoke()
    {
        return ['types' => ImportTypes::select()];
    }
}

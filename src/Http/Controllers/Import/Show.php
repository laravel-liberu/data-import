<?php

namespace LaravelLiberu\DataImport\Http\Controllers\Import;

use Illuminate\Routing\Controller;
use LaravelLiberu\DataImport\Services\Template;

class Show extends Controller
{
    public function __invoke(string $type)
    {
        return ['params' => (new Template($type))->params(false)];
    }
}

<?php

namespace LaravelLiberu\DataImport\Http\Controllers\Import;

use Illuminate\Routing\Controller;
use LaravelLiberu\DataImport\Models\RejectedImport;

class Rejected extends Controller
{
    public function __invoke(RejectedImport $rejected)
    {
        return $rejected->file->download();
    }
}

<?php

namespace LaravelLiberu\DataImport\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use LaravelLiberu\DataImport\Models\Import;
use LaravelLiberu\Users\Models\User;

class Notifiables
{
    public static function get(Import $import): Collection
    {
        $ids = explode(',', Config::get('enso.imports.notifiableIds'));

        return User::whereIn('id', $ids)
            ->where('id', '<>', $import->file->createdBy->id)
            ->get();
    }
}

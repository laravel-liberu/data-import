<?php

namespace LaravelLiberu\DataImport\Commands;

use Illuminate\Console\Command;
use LaravelLiberu\DataImport\Enums\Statuses;
use LaravelLiberu\DataImport\Models\Import;

class Purge extends Command
{
    protected $signature = 'enso:data-import:purge';

    protected $description = 'Removes old imports';

    public function handle()
    {
        Import::expired()->notDeletable()
            ->update(['status' => Statuses::Cancelled]);

        Import::expired()->deletable()->get()->each->purge();
    }
}

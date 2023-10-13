<?php

namespace LaravelLiberu\DataImport\Tests;

use LaravelLiberu\DataImport\Contracts\Importable;
use LaravelLiberu\DataImport\Models\Import;
use LaravelLiberu\Helpers\Services\Obj;
use LaravelLiberu\UserGroups\Models\UserGroup;

class UserGroupImporter implements Importable
{
    public function run(Obj $row, Import $import)
    {
        UserGroup::create($row->all());
    }
}

<?php

namespace LaravelEnso\DataImport\App\Models;

use Illuminate\Database\Eloquent\Model;
use LaravelEnso\Files\App\Contracts\Attachable;
use LaravelEnso\Files\App\Traits\HasFile;
use LaravelEnso\Helpers\App\Traits\CascadesMorphMap;

class RejectedImport extends Model implements Attachable
{
    use CascadesMorphMap, HasFile;

    protected $fillable = ['data_import_id'];

    public function dataImport()
    {
        return $this->belongsTo(DataImport::class);
    }

    public function folder(): string
    {
        return 'imports'
            .DIRECTORY_SEPARATOR
            .'rejected_'.$this->data_import_id;
    }
}

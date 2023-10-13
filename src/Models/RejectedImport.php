<?php

namespace LaravelLiberu\DataImport\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use LaravelLiberu\Files\Contracts\Attachable;
use LaravelLiberu\Files\Contracts\CascadesFileDeletion;
use LaravelLiberu\Files\Models\File;

class RejectedImport extends Model implements Attachable, CascadesFileDeletion
{
    protected $guarded = [];

    protected $folder = 'imports';

    public function import()
    {
        return $this->belongsTo(Import::class);
    }

    public function file(): Relation
    {
        return $this->belongsTo(File::class);
    }

    public static function cascadeFileDeletion(File $file): void
    {
        self::whereFileId($file->id)->first()?->delete();
    }

    public function delete()
    {
        $response = parent::delete();

        $this->file?->delete();

        return $response;
    }
}
